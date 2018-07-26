<?php

namespace App\Infrastructure\Persistence\Doctrine;

use App\Domain\Place\Place;
use App\Domain\Place\PlaceRepository;

class DoctrinePlaceRepository extends AbstractDoctrineRepository implements PlaceRepository
{
    protected function repositoryClassName(): string
    {
        return Place::class;
    }

    public function getCount(): int
    {
        return (int) $this
            ->em
            ->createQueryBuilder()
            ->select('count(p)')
            ->from(Place::class, 'p')
            ->getQuery()
            ->getSingleScalarResult();
    }
}
