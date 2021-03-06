<?php
/**
 * Created by PhpStorm.
 * User: jmercier
 * Date: 24/01/17
 * Time: 16:15
 */

namespace Jck\JsonApiResponse;

use Jck\JsonApiResponse\Exception\InvalidArgumentException;
use Jck\JsonApiResponse\Exception\RuntimeException;

class Errors extends AbstractJsonElement implements JsonElementInterface
{
    /**
     * @param string $key
     * @param $value
     * @return mixed
     * @throws InvalidArgumentException
     */
    protected function validateKeyValue(string $key, &$value): bool
    {
        if (is_array($value)) {
            $value = new Error($value);
        }
        if (! $value instanceof Error) {
            throw new InvalidArgumentException("Invalid type, array or a Error object expected");
        }
        return true;
    }

    /**
     * @return array
     * @throws RuntimeException
     */
    public function getArrayCopy(): array
    {
        $toReturn = [];
        /** @var Error $error */
        foreach($this as $error) {
            $toReturn[] = $error->jsonSerialize();
        }
        return $toReturn;
    }

    /**
     * @return bool
     */
    public function isValid(): bool
    {
        /** @var Error $error */
        foreach($this as $error) {
            if (! $error->isValid()) {
                return false;
            }
        }
        return true;
    }
}
