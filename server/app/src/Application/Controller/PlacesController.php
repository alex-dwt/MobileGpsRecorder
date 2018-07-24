<?php

namespace App\Application\Controller;

use App\Application\Handler\Place\CreatePlaceHandler;
use App\Application\Handler\Place\GetPlacesHandler;
use App\Application\Handler\Place\GetPlaceHandler;
use App\Domain\Place\Transformer\PlaceTransformer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/places")
 */
class PlacesController
{
    /**
     * @Route(methods={"POST"})
     */
    public function createAction(CreatePlaceHandler $handler, Request $request): array
    {
        $lat = (float) $request->request->get('lat');
        $lon = (float) $request->request->get('lon');

        if (!$lat || !$lon) {
            throw new UnprocessableEntityHttpException('Wrong lat/lon coordinates.');
        }

        return $handler->setTransformer(new PlaceTransformer())($lat, $lon);
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