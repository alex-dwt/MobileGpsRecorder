<?php

namespace App\Application\Handler\Place;

use App\Application\Handler\CommonHandler;
use App\Infrastructure\Persistence\Doctrine\DoctrinePlaceRepository;
use Elastica\Query\BoolQuery;
use Elastica\Query\GeoBoundingBox;
use FOS\ElasticaBundle\Finder\TransformedFinder;
use Elastica\Query\MatchAll;
use Elastica\Query\Filtered;

class GetPlacesInSquareHandler extends CommonHandler
{
    /**
     * @var TransformedFinder
     */
    private $placesElasticFinder;

    public function __construct(
        DoctrinePlaceRepository $placeRepository,
        $placesElasticFinder
    ) {
        $this->placesElasticFinder = $placesElasticFinder;
    }

    public function __invoke(string $topLeft, string $bottomRight)
    {
        return $this->returnValue(
            $this->placesElasticFinder->find(
                (new BoolQuery())
                    ->addMust(new MatchAll())
                    ->addFilter(new GeoBoundingBox(
                        'location',
                        [$topLeft, $bottomRight]
                    ))
            )
        );
    }
}
