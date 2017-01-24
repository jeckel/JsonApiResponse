<?php
/**
 * User: Julien MERCIER <jeckel@jeckel.fr>
 * Date: 19/01/17
 * Time: 17:29
 */

namespace Jck\JsonApiResponse;

use Jck\JsonApiResponse\Exception\Exception;
use Jck\JsonApiResponse\Exception\InvalidArgumentException;

/**
 * Class SingleDocument
 * @package Jck\JsonApiResponse
 */
class SingleDocument extends AbstractDocument
{
    /**
     * Config constructor.
     * @param array $values
     */
    public function __construct(array $values = [])
    {
        if (! isset($values['data'])) {
            $values['data'] = new Resource();
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
        if ($index == 'data') {
            if (is_array($value)) {
                $value = new Resource($value);
            }
            if (!$value instanceof Resource) {
                throw new InvalidArgumentException("Invalid value for 'data', array or a Resource object expected");
            }
        } else {
            return parent::validateKeyValue($index, $value);
        }
        return true;
    }

    /**
     * @return bool
     */
    public function isValid(): bool
    {
        return ($this->data->isEmpty() || $this->data->isValid()) && parent::isValid();
    }

    /**
     * @return bool
     */
    protected function isDataEmpty(): bool
    {
        return $this->data->isEmpty();
    }

    /**
     * @return bool
     */
    public function isEmpty(): bool
    {
        return $this->data->isEmpty() && parent::isEmpty();
    }

    /**
     * @param int $options
     * @param int $depth
     * @return string
     */
    public function json_encode(int $options = 0, int $depth = 512): string
    {
        return json_encode($this->jsonSerialize(), $options, $depth);
    }
}
