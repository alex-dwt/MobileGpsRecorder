<?php

namespace App\Application\Controller;

use App\Application\Handler\Place\CreatePlaceHandler;
use App\Application\Handler\Place\GetPlacesHandler;
use App\Application\Handler\Place\GetPlaceHandler;
use App\Application\Handler\Place\GetPlacesInSquareHandler;
use App\Application\Request\Places\CreatePlaceRequest;
use App\Application\Request\Places\InSquareRequest;
use App\Domain\Place\Transformer\PlaceInSquareTransformer;
use App\Domain\Place\Transformer\PlaceTransformer;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/places")
 */
class PlacesController
{
    /**
     * @Route(methods={"POST"})
     */
    public function createAction(CreatePlaceRequest $createPlaceRequest, CreatePlaceHandler $handler): array
    {
        return $handler
            ->setTransformer(new PlaceTransformer())(
                $createPlaceRequest
            );
    }

    /**
     * @Route(path="/in_square", methods={"GET"})
     */
    public function inSquareAction(InSquareRequest $inSquareRequest, GetPlacesInSquareHandler $handler): array
    {
        return [
            'items' => $handler
                ->setTransformer(new PlaceInSquareTransformer())(
                    $inSquareRequest->getTopLeft()['lat'] . ',' . $inSquareRequest->getTopLeft()['lon'],
                    $inSquareRequest->getBottomRight()['lat'] . ',' . $inSquareRequest->getBottomRight()['lon']
                )
        ];
    }

    /**
     * @Route(path="/{id}", methods={"GET"}, requirements={"id": "[\-a-z\d]+"})
     */
    public function viewAction(string $id, GetPlaceHandler $handler): array
    {
        return $handler->setTransformer(new PlaceTransformer())($id);
    }

    /**
     * @Route(methods={"GET"})
     */
    public function getListAction(GetPlacesHandler $handler): array
    {
        return [
            'items' => $handler->setTransformer(new PlaceTransformer())()
        ];
    }
}