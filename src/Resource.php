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

class Resource extends \ArrayObject implements JsonElementInterface
{
    const ALLOWED_KEYS = ['id', 'type', 'attributes', 'relationships'];

//    /**
//     * @var Relationships
//     */
//    protected $relationships;

    /**
     * Config constructor.
     * @param array $values
     */
    public function __construct(array $values = [])
    {
        foreach($values as $key=>$value) {
            $this->validateKeyValue($key, $value);
        }
        parent::__construct($values, self::ARRAY_AS_PROPS | self::STD_PROP_LIST);
    }

    /**
     * @param $index
     * @param $value
     * @return bool
     * @throws Exception
     */
    protected function validateKeyValue($index, &$value)
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
            case 'relationships' :
                // @Todo : to be implemented
                throw new Exception("Not implemented yet");
            default:
                throw new InvalidArgumentException("Invalid index, allowed : 'id', 'type', 'attributes', 'relationships'");
        }
        return true;
    }

    /**
     * Sets the value at the specified index to newval
     * @link http://php.net/manual/en/arrayobject.offsetset.php
     * @param mixed $index <p>
     * The index being set.
     * </p>
     * @param mixed $newval <p>
     * The new value for the <i>index</i>.
     * </p>
     * @return void
     * @since 5.0.0
     */
    public function offsetSet($index, $newval)
    {
        if ($this->validateKeyValue($index, $newval)) {
            parent::offsetSet($index, $newval);
        }
    }

    /**
     * Returns the value at the specified index
     * @link http://php.net/manual/en/arrayobject.offsetget.php
     * @param mixed $index <p>
     * The index with the value.
     * </p>
     * @return mixed The value at the specified index or false.
     * @since 5.0.0
     */
    public function offsetGet($index)
    {
        if (! in_array($index, self::ALLOWED_KEYS)) {
            return false;
        }
        $value = parent::offsetGet($index);
        if (! $value && $index == 'attributes') {
            $this->attributes = new Attributes();
            return $this->attributes;
        }
        return $value;
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
        $toReturn = ['type' => $this->type, 'id' => $this->id];
        if (! empty($this->attributes) && ! empty($attrs = $this->attributes->jsonSerialize())) {
            $toReturn['attributes'] = $attrs;
        }
        return $toReturn;
    }

    /**
     * @return bool
     */
    public function isValid(): bool
    {
        return !empty($this->type) && !empty($this->id);
    }
}
