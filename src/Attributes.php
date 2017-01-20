<?php
/**
 * User: Julien MERCIER <jeckel@jeckel.fr>
 * Date: 19/01/17
 * Time: 17:46
 */

namespace Jeckel\JsonApiResponse;

use Jeckel\JsonApiResponse\Exception\RuntimeException;

/**
 * Class Attributes
 * @package Jeckel\Scrum\Json
 */
class Attributes extends \ArrayObject implements JsonElementInterface
{
    /**
     * Config constructor.
     * @param array $values
     */
    public function __construct(array $values = [])
    {
        parent::__construct($values, self::ARRAY_AS_PROPS | self::STD_PROP_LIST);
    }

    /**
     * @return array
     * @throws RuntimeException
     */
    public function jsonSerialize(): array
    {
        return $this->getArrayCopy();
    }

    /**
     * @return bool
     */
    public function isValid(): bool
    {
        return true;
    }
}
