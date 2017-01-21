<?php
/**
 * User: Julien MERCIER <jeckel@jeckel.fr>
 * Date: 19/01/17
 * Time: 17:29
 */

namespace Jeckel\JsonApiResponse;


class CollectionDocument extends AbstractDocument
{
    /**
     * @var array
     */
    protected $data = [];

    public function add(Resource $resource): self
    {
        $this->collection[] = $resource;
        return $this;
    }
}
