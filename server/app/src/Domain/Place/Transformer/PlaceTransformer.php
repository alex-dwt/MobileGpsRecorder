<?php

namespace App\Domain\Place\Transformer;

use App\Domain\Common\DomainTransformer;
use App\Domain\Place\Place;

class PlaceTransformer extends DomainTransformer
{
    /**
     * @param Place $entity
     * @return array
     */
    protected function transformOneEntity($entity): array
    {
        return [
            'id' => $entity->getId(),
            'lat' => $entity->getLat(),
            'lon' => $entity->getLon(),
            'createdAt' => $entity->getCreatedAt()->format(\DateTime::ATOM),
        ];
    }
}
