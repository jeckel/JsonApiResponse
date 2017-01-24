<?php
/**
 * User: Julien MERCIER <jeckel@jeckel.fr>
 * Date: 19/01/17
 * Time: 17:46
 */

namespace Jck\JsonApiResponse;

use Jck\JsonApiResponse\Exception\InvalidArgumentException;
use Jck\JsonApiResponse\Exception\RuntimeException;

/**
 * Class Attributes
 * @package Jck\Scrum\Json
 */
class Attributes extends AbstractJsonElement
{
    /**
     * @param string $key
     * @param $value
     * @return mixed
     * @throws InvalidArgumentException
     */
    protected function validateKeyValue(string $key, &$value): bool
    {
        if (! is_scalar($value) && ! is_array($value) && ! is_null($value)) {
            throw new InvalidArgumentException("Value needs to be scalar or array");
        }
        return true;
    }

    /**
     * @return bool
     */
    public function isValid(): bool
    {
        return true;
    }
}
