<?php
/**
 * User: Julien MERCIER <jeckel@jeckel.fr>
 * Date: 20/01/17
 * Time: 17:55
 */

namespace Tests\Jeckel\JsonApiResponse\JsonApiResponse;


use Jeckel\JsonApiResponse\Link;
use Jeckel\JsonApiResponse\Meta;


class LinkTest extends \PHPUnit_Framework_TestCase
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
     * @expectedException \Jeckel\JsonApiResponse\Exception\InvalidArgumentException
     */
    public function testSetInvalidParams()
    {
        $link = new Link();
        $link->foo = 'bar';
    }

    /**
     * @expectedException \Jeckel\JsonApiResponse\Exception\InvalidArgumentException
     */
    public function testSetInvalidHref()
    {
        new Link(['href' => new \stdClass()]);
    }

    /**
     * @expectedException \Jeckel\JsonApiResponse\Exception\InvalidArgumentException
     */
    public function testSetInvalidMeta()
    {
        $link = new Link();
        $link->meta = new \stdClass();
    }

    /**
     * @expectedException \Jeckel\JsonApiResponse\Exception\RuntimeException
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
}
