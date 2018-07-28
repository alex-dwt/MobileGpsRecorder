<?php

namespace App\Domain\Place;

use App\Domain\Common\InitEntityTrait;
use App\Domain\Common\ToArrayTransformable;
use Doctrine\ORM\Mapping as ORM;

class AggregatedPin implements ToArrayTransformable
{
    /**
     * @var float
     */
    private $lat;

    /**
     * @var float
     */
    private $lon;

    /**
     * @var int
     */
    private $placesCount;

    /**
     * @var string
     */
    private $geohash;

    public function __construct(
        int $placesCount,
        string $geohash,
        float $lat,
        float $lon
    ) {
        $this->lat = $lat;
        $this->lon = $lon;
        $this->placesCount = $placesCount;
        $this->geohash = $geohash;
    }

    public function getPlacesCount(): int
    {
        return $this->placesCount;
    }

    public function getGeohash(): string
    {
        return $this->geohash;
    }

    public function toArray(): array
    {
        return [
            'lat' => $this->lat,
            'lon' => $this->lon,
            'placesCount' => $this->placesCount,
            'geohash' => $this->geohash,
            'id' => $this->geohash,
        ];
    }
}
