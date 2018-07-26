<?php

namespace App\Domain\Place;

interface PlaceRepository
{
    public function getCount(): int;
}