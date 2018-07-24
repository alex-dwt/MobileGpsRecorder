<?php

namespace App\Infrastructure\Persistence\Doctrine;

use App\Domain\Place\Place;

class DoctrinePlaceRepository extends AbstractDoctrineRepository
{
    protected function repositoryClassName(): string
    {
        return Place::class;
    }
}
