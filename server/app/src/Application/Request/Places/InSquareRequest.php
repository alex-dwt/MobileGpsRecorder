<?php

namespace App\Application\Request\Places;

use AlexDwt\VerifiedRequestBundle\Request\VerifiedRequest;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @method array getTopLeft
 * @method array getBottomRight
 */
class InSquareRequest extends VerifiedRequest
{
    public static function getValidationRules(): array
    {
        return [
            'topLeft' => [
                function (&$val) {
                    $val = (array) $val;
                },
                new Assert\Collection(['fields' => self::getPointRules()]),
            ],
            'bottomRight' => [
                function (&$val) {
                    $val = (array) $val;
                },
                new Assert\Collection(['fields' => self::getPointRules()]),
            ],
        ];
    }

    public static function getPointRules(): array
    {
        return [
            'lat' => [
                new Assert\NotBlank(),
                new Assert\Type(['type' => 'numeric']),
            ],
            'lon' => [
                new Assert\NotBlank(),
                new Assert\Type(['type' => 'numeric']),
            ],
        ];
    }
}
