<?php
/**
 * User: Julien MERCIER <jeckel@jeckel.fr>
 * Date: 19/01/17
 * Time: 17:26
 */

namespace Jeckel\Scrum\Json;

use Jeckel\Scrum\Json\Exception\RuntimeException;

class Links extends \ArrayObject implements JsonElementInterface
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
