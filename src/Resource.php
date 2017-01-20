<?php
/**
 * User: Julien MERCIER <jeckel@jeckel.fr>
 * Date: 19/01/17
 * Time: 17:21
 */

namespace Jeckel\Scrum\Json;


use Jeckel\Scrum\Json\Exception\RuntimeException;
use Prophecy\PhpDocumentor\ClassAndInterfaceTagRetriever;

class Resource implements JsonElementInterface
{
    /**
     * @var string
     */
    protected $type;

    /**
     * @var string
     */
    protected $id;

    /**
     * @var Attributes
     */
    protected $attributes;

    /**
     * @var Relationships
     */
    protected $relationships;

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return self
     */
    public function setType(string $type): self
    {
        // @todo validate regex : "a-zA-Z0-9\-\_"
        // http://jsonapi.org/format/#document-member-names
        $this->type = $type;
        return $this;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        // @todo validate regex : "a-zA-Z0-9\-\_"
        // http://jsonapi.org/format/#document-member-names
        return $this->id;
    }

    /**
     * @param string $id
     * @return self
     */
    public function setId(string $id): self
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @param Attributes|array $attributes
     * @return self
     */
    public function setAttributes($attributes): self
    {
        if ($attributes instanceof Attributes) {
            $this->attributes = $attributes;
        } else {
            if (empty($this->attributes)) {
                $this->attributes = new Attributes($attributes);
            } else {
                // @todo
            }
        }
        return $this;
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
        if (! empty($attrs = $this->attributes->jsonSerialize())) {
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
