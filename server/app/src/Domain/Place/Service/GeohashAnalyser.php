<?php

namespace App\Domain\Place\Service;

interface GeohashAnalyser
{
    /**
     * @param string $geohash
     * @return array Return [string topLeft, string bottomRight]
     */
    public function getGeohashCoordinates(string $geohash): array;

    /**
     * @param string $geohash
     * @return array Lat & Lon
     */
    public function getGeohashCenter(string $geohash): array;

    /**
     * @param string $from
     * @param string $to
     * @return int
     */
    public function getDistance(string $from, string $to): int;
}
