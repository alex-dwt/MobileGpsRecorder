<?php

namespace App\Domain\Place\Service;

use App\Domain\Place\AggregatedPin;
use App\Domain\Place\Place;
use Elastica\Aggregation\Filter;
use Elastica\Aggregation\GeohashGrid;
use Elastica\Query;
use FOS\ElasticaBundle\Finder\TransformedFinder;
use Elastica\Query\BoolQuery;
use Elastica\Query\GeoBoundingBox;
use Elastica\Query\MatchAll;

class GetPlacesInSquare
{
    /**
     * @var TransformedFinder
     */
    private $placesElasticFinder;

    /**
     * @var GeohashAnalyser
     */
    private $geohashAnalyser;

    public function __construct(
        $placesElasticFinder,
        GeohashAnalyser $geohashAnalyser
    ) {
        $this->placesElasticFinder = $placesElasticFinder;
        $this->geohashAnalyser = $geohashAnalyser;
    }

    /**
     * @param string $topLeft
     * @param string $bottomRight
     * @return array|AggregatedPin[]|Place[]
     */
    public function execute(string $topLeft, string $bottomRight): array
    {
        if (!$this->isAggregationShouldBeUsed($topLeft, $bottomRight)) {
            return $this->getPlacesInSquare($topLeft, $bottomRight);
        } else {
            $aggregatedPins = $this->getAggregatedPinsInSquare(
                $topLeft,
                $bottomRight
            );

            $pinsWithOnePlace = array_filter(
                $aggregatedPins,
                function (AggregatedPin $pin) {
                    return $pin->getPlacesCount() === 1;
                }
            );

            $result = array_values(
                array_diff_key($aggregatedPins, $pinsWithOnePlace)
            );

            foreach ($pinsWithOnePlace as $pin) {
                $result = array_merge(
                    $result,
                    $this->getPlacesInSquare(
                        ...$this->geohashAnalyser->getGeohashCoordinates(
                            $pin->getGeohash()
                        )
                    )
                );
            }

            return $result;
        }
    }

    private function getZoomForAggregation(string $topLeft, string $bottomRight): int
    {
        $distance = $this->geohashAnalyser->getDistance(
            $topLeft,
            $bottomRight
        );

//        4 -> 40
//        3 -> 160
//        2 -> 1300

        if ($distance > 3000) {
            return 2;
        } elseif ($distance > 600) {
            return 3;
        } else {
            return 4;
        }
    }

    /**
     * @param string $topLeft
     * @param string $bottomRight
     * @return array|Place[]
     */
    private function getPlacesInSquare(string $topLeft, string $bottomRight): array
    {
        return $this->placesElasticFinder->find(
            (new BoolQuery())
                ->addMust(new MatchAll())
                ->addFilter(new GeoBoundingBox(
                    'location',
                    [$topLeft, $bottomRight]
                )),
            10000
        );
    }

    /**
     * @param string $topLeft
     * @param string $bottomRight
     * @return array|AggregatedPin[]
     */
    private function getAggregatedPinsInSquare(string $topLeft, string $bottomRight): array
    {
        $agg = (new GeohashGrid('zoom1', 'location'))
            ->setPrecision(
                $this->getZoomForAggregation($topLeft, $bottomRight)
            );

        $aggFilter = (new Filter('zoomed-in'))
            ->setFilter(
                new GeoBoundingBox(
                    'location',
                    [$topLeft, $bottomRight]
                )
            )
            ->addAggregation($agg);

        $aggregations = $this
            ->placesElasticFinder
            ->createRawPaginatorAdapter(
                (new Query())->addAggregation($aggFilter)
            )
            ->getAggregations();

        $result = [];

        foreach ($aggregations['zoomed-in']['zoom1']['buckets'] ?? [] as $bucket) {
            $geohash = $bucket['key'];
            $result[] = new AggregatedPin(
                $bucket['doc_count'],
                $geohash,
                ...$this->geohashAnalyser->getGeohashCenter($geohash)
            );
        }

        return $result;
    }

    private function isAggregationShouldBeUsed(string $topLeft, string $bottomRight): bool
    {
        return $this
            ->geohashAnalyser
            ->getDistance(
                $topLeft,
                $bottomRight
            ) > 20;
    }
}
