<?php

namespace App\Domain\Place\Transformer;

use App\Domain\Common\DomainTransformer;
use App\Domain\Place\AggregatedPin;
use App\Domain\Place\Place;

class PlaceInSquareTransformer extends DomainTransformer
{
    /**
     * @param Place|AggregatedPin $entity
     * @return array
     */
    protected function transformOneEntity($entity): array
    {
        if ($entity instanceof Place) {
            return ['type' => 'place',]
                + (new PlaceTransformer())->transform($entity);
        } elseif ($entity instanceof AggregatedPin) {
            return ['type' => 'aggregatedPin',]
                + $entity->toArray();
        } else {
            throw new \LogicException();
        }
    }
}
