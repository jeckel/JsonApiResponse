<?php
/**
 * User: Julien MERCIER <jeckel@jeckel.fr>
 * Date: 19/01/17
 * Time: 17:21
 */

namespace Jck\JsonApiResponse;

use Jck\JsonApiResponse\Exception\Exception;
use Jck\JsonApiResponse\Exception\InvalidArgumentException;
use Jck\JsonApiResponse\Exception\RuntimeException;

class Resource extends AbstractJsonElement
{
//    const ALLOWED_KEYS = ['id', 'type', 'attributes', 'relationships', 'links', 'meta'];

    protected $default = [
        'attributes' => [],
        'meta'       => [],
        'links'      => []
    ];

    /**
     * @param string $index
     * @param $value
     * @return bool
     * @throws Exception
     */
    protected function validateKeyValue(string $index, &$value): bool
    {
        switch($index) {
            case 'id' :
            case 'type' :
                if (! is_string($value) || !preg_match('/^[a-zA-Z0-9\-]*$/', $value)) {
                    throw new InvalidArgumentException("Invalid format for $value, allowed characters are a-zA-Z0-9 - ");
                }
                break;
            case 'attributes' :
                return $this->validateAttributes($value);
                break;
            case 'meta' :
                return $this->validateMeta($value);
                break;
            case 'links' :
                return $this->validateLinks($value);
                break;
            // @Todo : to be implemented
//            case 'relationships' :
//                throw new Exception("Not implemented yet");
            default:
                throw new InvalidArgumentException("Invalid index, allowed : 'id', 'type', 'attributes', 'relationships'");
        }
        return true;
    }

    /**
     * @return bool
     */
    public function isValid(): bool
    {
        return !empty($this->type) && !empty($this->id);
    }

    /**
     * @return bool
     */
    public function isEmpty(): bool
    {
        return $this->count() == 3 &&
            $this->attributes->isEmpty() &&
            $this->links->isEmpty() &&
            $this->meta->isEmpty();
    }
}
