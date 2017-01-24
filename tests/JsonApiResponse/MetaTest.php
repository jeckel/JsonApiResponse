<?php
/**
 * User: Julien MERCIER <jeckel@jeckel.fr>
 * Date: 20/01/17
 * Time: 15:33
 */

namespace Tests\Jck\JsonApiResponse;

use Jck\JsonApiResponse\Meta;


class MetaTest extends \PHPUnit_Framework_TestCase
{
    public function testIsValid()
    {
        $meta = new Meta();
        $this->assertTrue($meta->isValid());
    }

    public function testAssignMeta()
    {
        $meta = new Meta();
        $meta->foo = 'bar';
        $this->assertEquals('bar', $meta->foo);

        $meta->bar = ['foo' => 'bar'];
        $this->assertEquals(['foo' => 'bar'], $meta->bar);

        $expected = ['foo' => 'bar', 'bar' => ['foo' => 'bar']];
        $this->assertEquals($expected, $meta->jsonSerialize());
    }

    /**
     * @expectedException \Jck\JsonApiResponse\Exception\InvalidArgumentException
     */
    public function testAssignWrongAttribute()
    {
        new Meta(['foo' => new \stdClass()]);
    }

    public function testConstructor()
    {
        $data = ['foo' => 'bar', 'bar' => ['foo' => 'bar']];
        $meta = new Meta($data);
        $this->assertEquals($data, $meta->jsonSerialize());
    }

    public function testIsEmpty()
    {
        $meta = new Meta();
        $this->assertTrue($meta->isEmpty());
        $meta->foo = 'bar';
        $this->assertFalse($meta->isEmpty());
    }
}
