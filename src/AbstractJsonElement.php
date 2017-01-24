<?php
/**
 * User: Julien MERCIER <jeckel@jeckel.fr>
 * Date: 20/01/17
 * Time: 17:12
 */

namespace Jck\JsonApiResponse;

use Jck\JsonApiResponse\Exception\InvalidArgumentException;
use Jck\JsonApiResponse\Exception\RuntimeException;

/**
 * Class AbstractJsonElement
 * @package Jck\JsonApiResponse
 */
abstract class AbstractJsonElement extends \ArrayObject implements JsonElementInterface
{
    /**
     * Config constructor.
     * @param array $values
     */
    public function __construct(array $values = [])
    {
        foreach($values as $key=>$value) {
            $this->validateKeyValue($key, $value);
            $values[$key] = $value;
        }
        parent::__construct($values, self::ARRAY_AS_PROPS | self::STD_PROP_LIST);
    }

    /**
     * @param string $key
     * @param $value
     * @return mixed
     * @throws InvalidArgumentException
     */
    abstract protected function validateKeyValue(string $key, &$value): bool;


    /**
     * @param $value
     * @return bool
     */
    protected function validateMeta(&$value): bool
    {
        if (is_array($value)) {
            $value = new Meta($value);
        }
        if (! $value instanceof Meta) {
            throw new InvalidArgumentException("Invalid value for 'meta', array or a Meta object expected");
        }
        return true;
    }

    /**
     * @param $value
     * @return bool
     */
    protected function validateLinks(&$value): bool
    {
        if (is_array($value)) {
            $value = new Links($value);
        }
        if (! $value instanceof Links) {
            throw new InvalidArgumentException("Invalid value for 'links', array or a Links object expected");
        }
        return true;
    }

    /**
     * @param $value
     * @return bool
     */
    protected function validateErrors(&$value): bool
    {
        if (is_array($value)) {
            $value = new Errors($value);
        }
        if (! $value instanceof Errors) {
            throw new InvalidArgumentException("Invalid value for 'errors', array of Errors object expected");
        }
        return true;
    }

    /**
     * @param $value
     * @return bool
     */
    protected function validateAttributes(&$value): bool
    {
        if (is_array($value)) {
            $value = new Attributes($value);
        }
        if (! $value instanceof Attributes) {
            throw new InvalidArgumentException("Invalid value for 'attribute', array or an Attributes object expected");
        }
        return true;
    }

    /**
     * Sets the value at the specified index to newval
     * @link http://php.net/manual/en/arrayobject.offsetset.php
     * @param mixed $index
     * @param mixed $newval
     * @return void
     * @throws InvalidArgumentException
     */
    public function offsetSet($index, $newval)
    {
        if ($this->validateKeyValue($index, $newval)) {
            parent::offsetSet($index, $newval);
        }
    }

    /**
     * @return array
     * @throws RuntimeException
     */
    public function jsonSerialize(): array
    {
        if (! $this->isValid()) {
            throw new RuntimeException("Can not export not valid element");
        }
        return $this->getArrayCopy();
    }

    /**
     * @return array
     */
    public function getArrayCopy()
    {
        return array_map(
            function (&$value) {
                if ($value instanceof JsonElementInterface) {
                    return $value->jsonSerialize();
                }
                return $v;
            },
            array_filter(
                parent::getArrayCopy(),
                function($value) {
                    if ($value instanceof JsonElementInterface) {
                        return !$value->isEmpty();
                    }
                    return true;
                }
            )
        );
    }

    /**
     * @return bool
     */
    public function isEmpty(): bool
    {
        return $this->count() == 0;
    }
}
