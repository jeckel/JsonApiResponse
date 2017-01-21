<?php
/**
 * User: Julien MERCIER <jeckel@jeckel.fr>
 * Date: 19/01/17
 * Time: 17:29
 */

namespace Jeckel\JsonApiResponse;


use Jeckel\JsonApiResponse\Exception\Exception;
use Jeckel\JsonApiResponse\Exception\InvalidArgumentException;

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
     * @return array
     */
    public function getArrayCopy()
    {
        $array = parent::getArrayCopy();
        if (! $this->data->isEmpty()) {
            $array['data'] = $this->data->jsonSerialize();
        } else {
            unset($array['data']);
        }
        return $array;
    }

    /**
     * @return bool
     */
    public function isValid(): bool
    {
        return $this->data->isValid() && parent::isValid();
    }

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
}
