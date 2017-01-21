<?php
/**
 * User: Julien MERCIER <jeckel@jeckel.fr>
 * Date: 19/01/17
 * Time: 17:21
 */

namespace Jeckel\JsonApiResponse;

use Jeckel\JsonApiResponse\Exception\Exception;
use Jeckel\JsonApiResponse\Exception\InvalidArgumentException;
use Jeckel\JsonApiResponse\Exception\RuntimeException;

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
            // @Todo : to be implemented
//            case 'relationships' :
//                throw new Exception("Not implemented yet");
//            case 'links' :
//                throw new Exception("Not implemented yet");
//            case 'meta' :
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
        if (count($array['attributes']) > 0) {
            $array['attributes'] = $array['attributes']->jsonSerialize();
        } else {
            unset($array['attributes']);
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
}
