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
        if (! isset($values['errors'])) {
            $values['errors'] = new Errors();
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
            case 'meta' :
                return $this->validateMeta($value);
            case 'links' :
                return $this->validateLinks($value);
                break;
            case 'errors' :
                return $this->validateErrors($value);
                break;
            // @Todo : to be implemented
//            case 'jsonapi' :
//            case 'included' :
//                throw new Exception("Not implemented yet");
            default:
                throw new InvalidArgumentException("Invalid index, allowed : 'data', 'errors', 'meta', 'links', 'jsonapi', 'included'");
        }
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
     * @param int $options
     * @param int $depth
     * @return string
     */
    public function jsonEncode(int $options = 0, int $depth = 512): string
    {
        return json_encode($this->jsonSerialize(), $options, $depth);
    }
}
