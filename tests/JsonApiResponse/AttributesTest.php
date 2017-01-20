<?php
/**
 * User: Julien MERCIER <jeckel@jeckel.fr>
 * Date: 20/01/17
 * Time: 15:33
 */

namespace Tests\Jeckel\JsonApiResponse;

use Jeckel\JsonApiResponse\Attributes;


class AttributesTest extends \PHPUnit_Framework_TestCase
{
    public function testIsValid()
    {
        $attributes = new Attributes();
        $this->assertTrue($attributes->isValid());
    }

    public function testAssignAttributes()
    {
        $attributes = new Attributes();
        $attributes->foo = 'bar';
        $this->assertEquals('bar', $attributes->foo);

        $attributes->bar = ['foo' => 'bar'];
        $this->assertEquals(['foo' => 'bar'], $attributes->bar);

        $expected = ['foo' => 'bar', 'bar' => ['foo' => 'bar']];
        $this->assertEquals($expected, $attributes->jsonSerialize());
    }

    /**
     * @expectedException \Jeckel\JsonApiResponse\Exception\InvalidArgumentException
     */
    public function testAssignWrongAttribute()
    {
        new Attributes(['foo' => new \stdClass()]);
    }

    public function testConstructor()
    {
        $data = ['foo' => 'bar', 'bar' => ['foo' => 'bar']];
        $attributes = new Attributes($data);
        $this->assertEquals($data, $attributes->jsonSerialize());
    }
}
