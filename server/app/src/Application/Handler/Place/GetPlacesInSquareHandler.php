<?php

namespace App\Application\Handler\Place;

use App\Application\Handler\CommonHandler;
use App\Domain\Place\Service\GetPlacesInSquare;

class GetPlacesInSquareHandler extends CommonHandler
{
    /**
     * @var GetPlacesInSquare
     */
    private $getPlacesInSquare;

    public function __construct(
        GetPlacesInSquare $getPlacesInSquare
    ) {
        $this->getPlacesInSquare = $getPlacesInSquare;
    }

    public function __invoke(
        string $topLeft,
        string $bottomRight
    ) {
        return $this->returnValue(
            $this->getPlacesInSquare->execute(
                $topLeft,
                $bottomRight
            )
        );
    }
}
