<?php
/**
 * User: Julien MERCIER <jeckel@jeckel.fr>
 * Date: 19/01/17
 * Time: 17:24
 */

namespace Jck\Scrum\Json;


use Jck\JsonApiResponse\AbstractJsonElement;
use Jck\JsonApiResponse\Exception\InvalidArgumentException;

/**
 * Class Error
 * @package Jck\Scrum\Json
 */
class Error extends AbstractJsonElement
{
    /**
     * @param string $index
     * @param $value
     * @return bool
     * @throws Exception
     */
    protected function validateKeyValue(string $index, &$value): bool
    {
        switch ($index) {
            case 'status' :
                // test it's 4xx or 5xx
                break;
            case 'title' :
            case 'detail' :
                if (! is_string($value)) {
                    throw new InvalidArgumentException("Field $index should be a string");
                }
                break;

            /* @todo : to be implemented
            case 'id' :
            case 'links' :
            case 'code' :
            case 'source' :
            case 'meta' : */
            default:
                throw new InvalidArgumentException("Unkown attribute $index");
        }
        return true;
    }

    /**
     * @return bool
     */
    public function isValid(): bool
    {
        return !$this->isEmpty();
    }


}
