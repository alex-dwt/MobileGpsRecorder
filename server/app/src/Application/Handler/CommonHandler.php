<?php

namespace App\Application\Handler;

use App\Domain\Common\DomainTransformer;

abstract class CommonHandler
{
    /**
     * @var DomainTransformer|null
     */
    protected $transformer;

    public function setTransformer(DomainTransformer $transformer): self
    {
        $this->transformer = $transformer;

        return $this;
    }

    protected function returnValue($value = null)
    {
        if (is_null($value)) {
            return null;
        }

        if (is_null($this->transformer)) {
            throw new \RuntimeException('Set transformer please...');
        }

        return $this->transformer->transform($value);
    }
}
