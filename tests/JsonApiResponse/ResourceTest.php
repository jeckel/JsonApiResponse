<?php
/**
 * User: Julien MERCIER <jeckel@jeckel.fr>
 * Date: 20/01/17
 * Time: 15:56
 */

namespace Tests\Jck\JsonApiResponse;

use Jck\JsonApiResponse\Attributes;
use Jck\JsonApiResponse\Exception\InvalidArgumentException;
use Jck\JsonApiResponse\Links;
use Jck\JsonApiResponse\Meta;
use Jck\JsonApiResponse\Resource;
use PHPUnit\Framework\TestCase;

class ResourceTest extends TestCase
{
    public function testSetId()
    {
        $resource = new Resource();
        $resource->id = 'foo';
        $this->assertEquals('foo', $resource->id);
    }

    /**
     * @expectedException \Jck\JsonApiResponse\Exception\InvalidArgumentException
     */
    public function testSetWrongId()
    {
        $resource = new Resource();
        $resource->id = '[tutu]';
    }

    public function testSetType()
    {
        $resource = new Resource();
        $resource->type = 'foo';
        $this->assertEquals('foo', $resource->type);
    }

    /**
     * @expectedException \Jck\JsonApiResponse\Exception\InvalidArgumentException
     */
    public function testSetWrongType()
    {
        $resource = new Resource();
        $resource->type = '[tutu]';
    }

    public function testConstruct()
    {
        $resource = new Resource(['id' => 'foo', 'type' => 'bar']);
        $this->assertEquals('foo', $resource->id);
        $this->assertEquals('bar', $resource->type);
    }

    /**
     * @expectedException \Jck\JsonApiResponse\Exception\InvalidArgumentException
     */
    public function testConstructWithWrongId()
    {
        new Resource(['id' => "{tutu]"]);
    }

    public function testIsValid()
    {
        $resource = new Resource();
        $this->assertFalse($resource->isValid());

        $resource->id = 'foo-bar';
        $this->assertFalse($resource->isValid());

        $resource->type = 'babar';
        $this->assertTrue($resource->isValid());

        $resource = new Resource();
        $resource->type = 'foo';
        $this->assertFalse($resource->isValid());
    }

    public function testGetEmptyAttributes()
    {
        $resource = new Resource(['id' => 'foo', 'type' => 'bar']);
        $attributes = $resource->attributes;
        $this->assertInstanceOf(Attributes::class, $attributes);
    }

    public function testGetEmptyLinks()
    {
        $resource = new Resource(['id' => 'foo', 'type' => 'bar']);
        $links = $resource->links;
        $this->assertInstanceOf(Links::class, $links);
    }

    public function testGetEmptyMeta()
    {
        $resource = new Resource(['id' => 'foo', 'type' => 'bar']);
        $meta = $resource->meta;
        $this->assertInstanceOf(Meta::class, $meta);
    }

    public function testCreateAttributesByArray()
    {
        $resource = new Resource(['id' => 'foo', 'type' => 'bar']);
        $data = ['foo' => 'bar'];
        $resource->attributes = $data;
        $this->assertInstanceOf(Attributes::class, $resource->attributes);
        $this->assertEquals('bar', $resource->attributes->foo);
    }

    public function testCreateLinksByArray()
    {
        $resource = new Resource(['id' => 'foo', 'type' => 'bar']);
        $data = ['foo' => 'bar'];
        $resource->links = $data;
        $this->assertInstanceOf(Links::class, $resource->links);
        $this->assertEquals('bar', $resource->links->foo);
    }

    public function testCreateMetaByArray()
    {
        $resource = new Resource(['id' => 'foo', 'type' => 'bar']);
        $data = ['foo' => 'bar'];
        $resource->meta = $data;
        $this->assertInstanceOf(Meta::class, $resource->meta);
        $this->assertEquals('bar', $resource->meta->foo);
    }

    /**
     * @expectedException \Jck\JsonApiResponse\Exception\InvalidArgumentException
     */
    public function testSetUnknownProperty()
    {
        new Resource(['foo' => 'bar']);
    }

    /**
     * @expectedException \Jck\JsonApiResponse\Exception\InvalidArgumentException
     */
    public function testCreateAttributesWithWrongType()
    {
        new Resource(['attributes' => new \stdClass()]);
    }

    /**
     * @expectedException \Jck\JsonApiResponse\Exception\InvalidArgumentException
     */
    public function testCreateLinksWithWrongType()
    {
        new Resource(['links' => new \stdClass()]);
    }

    /**
     * @expectedException \Jck\JsonApiResponse\Exception\InvalidArgumentException
     */
    public function testCreateMetaWithWrongType()
    {
        new Resource(['meta' => new \stdClass()]);
    }

    /**
     * @expectedException \Jck\JsonApiResponse\Exception\RuntimeException
     */
    public function testJsonSerializeIsNotValid()
    {
        $resource = new Resource();
        $resource->jsonSerialize();
    }

    public function testJsonSerialize()
    {
        $resource = new Resource(['id' => 'foo', 'type' => 'bar']);
        $this->assertEquals(['id' => 'foo', 'type' => 'bar'], $resource->jsonSerialize());

        // test with attributes, links and meta
        $resource->attributes->foo = 'bar';
        $resource->attributes->bar = ['foobar' => 'barbaz'];
        $resource->meta->bar = 'bar';
        $resource->meta->foobar = ['foo' => 'bar'];
        $resource->links->self = 'http://foo.com/bar/256';

        $expected = [
            'id' => 'foo',
            'type' => 'bar',
            'attributes' => ['foo' => 'bar', 'bar' => ['foobar' => 'barbaz']],
            'meta' => ['bar' => 'bar', 'foobar' => ['foo' => 'bar']],
            'links' => ['self' =>  'http://foo.com/bar/256']
        ];
        $this->assertEquals($expected, $resource->jsonSerialize());
    }

    public function testJsonSerializeWithEmptyAttributesLinksMeta()
    {
        $resource = new Resource(['id' => 'foo', 'type' => 'bar']);
        $this->assertInstanceOf(Attributes::class, $resource->attributes);
        $this->assertInstanceOf(Links::class, $resource->links);
        $this->assertInstanceOf(Meta::class, $resource->meta);
        $this->assertEquals(['id' => 'foo', 'type' => 'bar'], $resource->jsonSerialize());
    }

    public function testIsEmpty()
    {
        $resource = new Resource();
        $this->assertTrue($resource->isEmpty());
        $resource->id = 'bar';
        $this->assertFalse($resource->isEmpty());
    }
}
