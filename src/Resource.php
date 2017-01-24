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
    const ALLOWED_KEYS = ['id', 'type', 'attributes', 'relationships', 'links', 'meta'];

    /**
     * Config constructor.
     * @param array $values
     */
    public function __construct(array $values = [])
    {
        if (! isset($values['attributes'])) {
            $values['attributes'] = new Attributes;
        }
        if (! isset($values['meta'])) {
            $values['meta'] = new Meta;
        }
        if (! isset($values['links'])) {
            $values['links'] = new Links;
        }
        parent::__construct($values);
    }

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
                if (is_array($value)) {
                    $value = new Attributes($value);
                }
                if (! $value instanceof Attributes) {
                    throw new InvalidArgumentException("Invalid value for 'attribute', array or an Attributes object expected");
                }
                break;
            case 'meta' :
                if (is_array($value)) {
                    $value = new Meta($value);
                }
                if (! $value instanceof Meta) {
                    throw new InvalidArgumentException("Invalid value for 'meta', array or a Meta object expected");
                }
                break;
            case 'links' :
                if (is_array($value)) {
                    $value = new Links($value);
                }
                if (! $value instanceof Links) {
                    throw new InvalidArgumentException("Invalid value for 'links', array or a Links object expected");
                }
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
     * @return array
     */
    public function getArrayCopy()
    {
        $array = parent::getArrayCopy();
        if (! $this->attributes->isEmpty()) {
            $array['attributes'] = $this->attributes->jsonSerialize();
        } else {
            unset($array['attributes']);
        }
        if (! $this->meta->isEmpty()) {
            $array['meta'] = $this->meta->jsonSerialize();
        } else {
            unset($array['meta']);
        }
        if (! $this->links->isEmpty()) {
            $array['links'] = $this->links->jsonSerialize();
        } else {
            unset($array['links']);
        }
        return $array;
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
