<?php

namespace App\Application\Handler\Place;

use App\Application\Handler\CommonHandler;
use App\Domain\Place\Exception\PlaceNotFoundException;
use App\Infrastructure\Persistence\Doctrine\DoctrinePlaceRepository;

class GetPlaceHandler extends CommonHandler
{
    /**
     * @var DoctrinePlaceRepository
     */
    private $placeRepository;

    public function __construct(DoctrinePlaceRepository $placeRepository)
    {
        $this->placeRepository = $placeRepository;
    }

    public function __invoke(string $id)
    {
        if (!$place = $this->placeRepository->get($id)) {
            throw new PlaceNotFoundException();
        }

        return $this->returnValue($place);
    }
}
