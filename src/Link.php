<?php
/**
 * User: Julien MERCIER <jeckel@jeckel.fr>
 * Date: 20/01/17
 * Time: 17:47
 */

namespace Jeckel\JsonApiResponse;


use Jeckel\JsonApiResponse\Exception\InvalidArgumentException;

class Link extends AbstractJsonElement
{
    const ALLOWED_KEYS = ['href', 'meta'];

    /**
     * Config constructor.
     * @param array $values
     */
    public function __construct(array $values = [])
    {
        if (! isset($values['meta'])) {
            $values['meta'] = new Meta;
        }
        parent::__construct($values);
    }


    /**
     * @param string $key
     * @param $value
     * @return mixed
     * @throws InvalidArgumentException
     */
    protected function validateKeyValue(string $key, &$value): bool
    {
        switch($key) {
            case 'href' :
                if (! is_string($value)) {
                    throw new InvalidArgumentException("Invalid format for $key, string expected");
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
        if (! $this->meta->isEmpty()) {
            $array['meta'] = $this->meta->jsonSerialize();
        } else {
            unset($array['meta']);
        }
        return $array;
    }

    /**
     * @return bool
     */
    public function isValid(): bool
    {
        return ! empty($this->href);
    }

    /**
     * @return bool
     */
    public function isEmpty(): bool
    {
        return $this->meta->isEmpty() && empty($this->href);
    }

}
