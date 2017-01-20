<?php
/**
 * User: Julien MERCIER <jeckel@jeckel.fr>
 * Date: 19/01/17
 * Time: 17:26
 */

namespace Jeckel\JsonApiResponse;


use Jeckel\JsonApiResponse\Exception\InvalidArgumentException;

class Links extends AbstractJsonElement
{
    /**
     * @param string $key
     * @param $value
     * @return mixed
     * @throws InvalidArgumentException
     */
    protected function validateKeyValue(string $key, &$value): bool
    {
        if (is_string($value)) {
            /* @todo : validate if it's an url ? */
            return true;
        }
        if ($value instanceof Link) {
            return true;
        }
        if (is_array($value)) {
            $value = new Link($value);
            return true;
        }
        throw new InvalidArgumentException("Invalid link provided, expected url / Link object / array");
    }

    /**
     * @return array
     */
    public function getArrayCopy()
    {
        $array = parent::getArrayCopy();
        foreach($array as $key=>$value) {
            if ($value instanceof Link) {
                $array[$key] = $value->jsonSerialize();
            }
        }
        return $array;
    }

    /**
     * @return bool
     */
    public function isValid(): bool
    {
        return true;
    }
}
