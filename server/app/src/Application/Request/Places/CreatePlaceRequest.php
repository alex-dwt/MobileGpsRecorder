<?php

namespace App\Application\Request\Places;

use AlexDwt\VerifiedRequestBundle\Request\VerifiedRequest;

/**
 * @method string getLat
 * @method string getLon
 */
class CreatePlaceRequest extends VerifiedRequest
{
    public static function getValidationRules(): array
    {
        return InSquareRequest::getPointRules();
    }
}
