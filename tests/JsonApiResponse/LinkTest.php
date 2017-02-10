<?php
/**
 * User: Julien MERCIER <jeckel@jeckel.fr>
 * Date: 20/01/17
 * Time: 17:55
 */

namespace Tests\Jck\JsonApiResponse\JsonApiResponse;

use Jck\JsonApiResponse\Link;
use Jck\JsonApiResponse\Meta;
use PHPUnit\Framework\TestCase;

class LinkTest extends TestCase
{
    public function testConstruct()
    {
        $link = new Link(['href' => 'http://foo/bar', 'meta' => ['foo' => 'bar']]);
        $this->assertEquals('http://foo/bar', $link->href);
        $meta = $link->meta;
        $this->assertInstanceOf(Meta::class, $meta);
        $this->assertEquals('bar', $meta->foo);
    }

    /**
     * @expectedException \Jck\JsonApiResponse\Exception\InvalidArgumentException
     */
    public function testSetInvalidParams()
    {
        $link = new Link();
        $link->foo = 'bar';
    }

    /**
     * @expectedException \Jck\JsonApiResponse\Exception\InvalidArgumentException
     */
    public function testSetInvalidHref()
    {
        new Link(['href' => new \stdClass()]);
    }

    /**
     * @expectedException \Jck\JsonApiResponse\Exception\InvalidArgumentException
     */
    public function testSetInvalidMeta()
    {
        $link = new Link();
        $link->meta = new \stdClass();
    }

    /**
     * @expectedException \Jck\JsonApiResponse\Exception\RuntimeException
     */
    public function testIsNotValid()
    {
        (new Link())->jsonSerialize();
    }

    public function testJsonSerialize()
    {
        $link = new Link(['href' => 'http://foo/bar']);
        $this->assertEquals(['href' => 'http://foo/bar'], $link->jsonSerialize());

        $link->meta->foo = 'bar';
        $this->assertEquals(['href' => 'http://foo/bar', 'meta' => ['foo' => 'bar']], $link->jsonSerialize());
    }

    public function testIsEmpty()
    {
        $link = new Link();
        $this->assertTrue($link->isEmpty());
        $link->href = 'http://foo/bar';
        $this->assertFalse($link->isEmpty());
    }
}
