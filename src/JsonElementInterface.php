<?php
/**
 * User: Julien MERCIER <jeckel@jeckel.fr>
 * Date: 19/01/17
 * Time: 17:34
 */

namespace Jeckel\JsonApiResponse;

use Jeckel\JsonApiResponse\Exception\RuntimeException;

/**
 * Interface JsonElementInterface
 * @package Jeckel\Scrum\Json
 */
interface JsonElementInterface
{
    /**
     * @return array
     * @throws RuntimeException
     */
    public function jsonSerialize(): array;

    /**
     * @return bool
     */
    public function isValid(): bool;

    /**
     * @return bool
     */
    public function isEmpty(): bool;
}
