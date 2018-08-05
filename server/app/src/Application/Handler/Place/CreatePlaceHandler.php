<?php

namespace App\Application\Handler\Place;

use App\Application\Handler\CommonHandler;
use App\Application\Request\Places\CreatePlaceRequest;
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

    public function __invoke(CreatePlaceRequest $request)
    {
        $place = new Place(
            (float) $request->getLat(),
            (float) $request->getLon()
        );

        $this->placeRepository->add($place);

        return $this->returnValue($place);
    }
}
