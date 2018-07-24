<?php

namespace App\Application\Handler\Place;

use App\Application\Handler\CommonHandler;
use App\Infrastructure\Persistence\Doctrine\DoctrinePlaceRepository;

class GetPlacesHandler extends CommonHandler
{
    /**
     * @var DoctrinePlaceRepository
     */
    private $placeRepository;

    public function __construct(DoctrinePlaceRepository $placeRepository)
    {
        $this->placeRepository = $placeRepository;
    }

    public function __invoke()
    {
        return $this->returnValue(
            $this->placeRepository->getAll()
        );
    }
}
