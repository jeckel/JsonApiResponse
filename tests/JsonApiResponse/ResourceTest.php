<?php
/**
 * User: Julien MERCIER <jeckel@jeckel.fr>
 * Date: 20/01/17
 * Time: 15:56
 */

namespace Tests\Jeckel\JsonApiResponse;


use Jeckel\JsonApiResponse\Attributes;
use Jeckel\JsonApiResponse\Exception\InvalidArgumentException;
use Jeckel\JsonApiResponse\Resource;


class ResourceTest extends \PHPUnit_Framework_TestCase
{
    public function testSetId()
    {
        $resource = new Resource();
        $resource->id = 'foo';
        $this->assertEquals('foo', $resource->id);
    }

    /**
     * @expectedException Jeckel\JsonApiResponse\Exception\InvalidArgumentException
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
     * @expectedException Jeckel\JsonApiResponse\Exception\InvalidArgumentException
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
     * @expectedException Jeckel\JsonApiResponse\Exception\InvalidArgumentException
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

    public function testCreateAttributesByArray()
    {
        $resource = new Resource(['id' => 'foo', 'type' => 'bar']);
        $data = ['foo' => 'bar'];
        $resource->attributes = $data;
        $this->assertInstanceOf(Attributes::class, $resource->attributes);
        $this->assertEquals('bar', $resource->attributes->foo);
    }

    /**
     * @expectedException Jeckel\JsonApiResponse\Exception\RuntimeException
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

        // test with attributes
        $resource->attributes = ['foo' => 'bar'];
        $resource->attributes->bar = ['foobar' => 'barbaz'];

        $expected = ['id' => 'foo', 'type' => 'bar', 'attributes' => ['foo' => 'bar', 'bar' => ['foobar' => 'barbaz']]];
        $this->assertEquals($expected, $resource->jsonSerialize());
    }
}
