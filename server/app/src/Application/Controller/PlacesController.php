<?php

namespace App\Application\Controller;

use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/places")
 */
class PlacesController
{
    /**
     * @Route(methods={"POST"})
     */
    public function createAction(): array
    {
        return [111=>222];
    }


    /**
     * @Route(path="/{id}", methods={"GET"}, requirements={"id": "[\-a-z\d]+"})
     */
    public function viewAction(string $id): array
    {
        return [111=>222];
    }
}