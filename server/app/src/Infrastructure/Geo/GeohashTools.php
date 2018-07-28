<?php

namespace App\Infrastructure\Geo;

use App\Domain\Place\Service\GeohashAnalyser;
use League\Geotools\Coordinate\Coordinate;
use League\Geotools\Geotools;

class GeohashTools implements GeohashAnalyser
{
    public function getGeohashCoordinates(string $geohash): array
    {
        [$southWest, $northEast] = $this
            ->getGeotools()
            ->geohash()
            ->decode($geohash)
            ->getBoundingBox();

        return [
            (float) $northEast->getLatitude() . ',' . (float) $southWest->getLongitude(),
            (float) $southWest->getLatitude() . ',' . (float) $northEast->getLongitude(),
        ];
    }

    public function getGeohashCenter(string $geohash): array
    {
        $decoded = $this->getGeotools()->geohash()->decode($geohash);

        return [
            (float) $decoded->getCoordinate()->getLatitude(),
            (float) $decoded->getCoordinate()->getLongitude(),
        ];
    }

    public function getDistance(string $from, string $to): int
    {
        return (int) ceil(
            $this
                ->getGeotools()
                ->distance()
                ->setFrom(new Coordinate($from))
                ->setTo(new Coordinate($to))
                ->in('km')
                ->vincenty()
        );
    }

    private function getGeotools(): Geotools
    {
        static $geotools;

        if (!$geotools) {
            $geotools = new \League\Geotools\Geotools();
        }

        return $geotools;
    }
}
