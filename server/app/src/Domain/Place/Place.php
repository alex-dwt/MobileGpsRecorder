<?php

namespace App\Domain\Place;

use App\Domain\Common\InitEntityTrait;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class Place
{
    use InitEntityTrait;

    /**
     * @var float
     * @ORM\Column(type="decimal", precision=16, scale=12, nullable=false)
     */
    private $lat;

    /**
     * @var float
     * @ORM\Column(type="decimal", precision=16, scale=12, nullable=false)
     */
    private $lon;

    public function __construct(
        float $lat,
        float $lon
    ){
        $this->init();

        $this->lat = $lat;
        $this->lon = $lon;
    }

    public function getLat(): float
    {
        return $this->lat;
    }

    public function getLon(): float
    {
        return $this->lon;
    }

    public function getLocation()
    {
        return "$this->lat,$this->lon";
    }
}
