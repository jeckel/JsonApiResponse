<?php
/**
 * User: Julien MERCIER <jeckel@jeckel.fr>
 * Date: 19/01/17
 * Time: 17:29
 */

namespace Jeckel\Scrum\Json;


class SingleDocument extends AbstractDocument
{
    /**
     * @var Resource
     */
    protected $data;

    /**
     * @return Resource
     */
    public function getData(): Resource
    {
        if (empty($this->data)) {
            $this->data = new Resource();
        }
        return $this->data;
    }

    /**
     * @param Resource $data
     * @return self
     */
    public function setData($data): self
    {
        $this->data = $data;
        return $this;
    }

    /**
     * @return array
     */
    protected function jsonSerializeData(): array
    {
        return $this->data->jsonSerialize();
    }
}
