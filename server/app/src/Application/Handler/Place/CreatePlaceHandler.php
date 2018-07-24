<?php

namespace App\Application\Handler\Place;

use App\Application\Handler\CommonHandler;
use App\Domain\Place\Place;
use App\Infrastructure\Persistence\Doctrine\DoctrinePlaceRepository;

class CreatePlaceHandler extends CommonHandler
{
    /**
     * @var DoctrinePlaceRepository
     */
    private $placeRepository;

    public function __construct(DoctrinePlaceRepository $placeRepository)
    {
        $this->placeRepository = $placeRepository;
    }

    public function __invoke(float $lat, float $lon)
    {
        $this->placeRepository->add(
            $place = new Place($lat, $lon)
        );

        return $this->returnValue($place);
    }
}
