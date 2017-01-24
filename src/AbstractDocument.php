<?php
/**
 * User: Julien MERCIER <jeckel@jeckel.fr>
 * Date: 19/01/17
 * Time: 17:23
 */

namespace Jck\JsonApiResponse;

use Jck\JsonApiResponse\Exception\Exception;
use Jck\JsonApiResponse\Exception\InvalidArgumentException;

abstract class AbstractDocument extends AbstractJsonElement
{
//    const ALLOWED_KEYS = ['data', 'errors', 'meta', 'links', 'jsonapi', 'included'];

    /**
     * Config constructor.
     * @param array $values
     */
    public function __construct(array $values = [])
    {
        if (! isset($values['meta'])) {
            $values['meta'] = new Meta;
        }
        if (! isset($values['links'])) {
            $values['links'] = new Links;
        }
        if (! isset($values['errors'])) {
            $values['errors'] = new Errors();
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
            case 'errors' :
                if (is_array($value)) {
                    $value = new Errors($value);
                }
                if (! $value instanceof Errors) {
                    throw new InvalidArgumentException("Invalid value for 'errors', array of Errors object expected");
                }
                break;
            // @Todo : to be implemented
//            case 'jsonapi' :
//            case 'included' :
//                throw new Exception("Not implemented yet");
            default:
                throw new InvalidArgumentException("Invalid index, allowed : 'data', 'errors', 'meta', 'links', 'jsonapi', 'included'");
        }
        return true;
    }

    /**
     * @return bool
     */
    public function isValid(): bool
    {
        if ($this->isDataEmpty() && $this->errors->isEmpty() && $this->meta->isEmpty()) {
            return false;
        }
        /*if (empty($this->data) && !empty($this->included)) {
            return false;
        }*/
        return ($this->links->isEmpty() || $this->links->isValid()) &&
            ($this->meta->isEmpty() || $this->meta->isValid()) &&
            ($this->errors->isEmpty() || $this->errors->isValid());
    }

    /**
     * @return bool
     */
    abstract protected function isDataEmpty(): bool;

    /**
     * @return bool
     */
    public function isEmpty(): bool
    {
        return $this->links->isEmpty() &&
            $this->meta->isEmpty() &&
            $this->errors->isEmpty();
    }

    /**
     * @return string
     */
    public function toJson(): string
    {
        return json_encode($this->getArrayCopy());
    }
}
