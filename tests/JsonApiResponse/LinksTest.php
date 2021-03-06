<?php
/**
 * User: Julien MERCIER <jeckel@jeckel.fr>
 * Date: 20/01/17
 * Time: 18:19
 */

namespace Tests\Jck\JsonApiResponse\JsonApiResponse;

use Jck\JsonApiResponse\Link;
use Jck\JsonApiResponse\Links;
use Jck\JsonApiResponse\Meta;
use PHPUnit\Framework\TestCase;

class LinksTest extends TestCase
{
    public function testConstruct()
    {
        $links = new Links(['self' => 'http://foo/bar', 'related' => ['href' => 'http://bar/foo', 'meta' => ['foo' => 'bar']]]);
        $this->assertEquals('http://foo/bar',  $links->self);
        $link = $links->related;
        $this->assertInstanceOf(Link::class, $link);
        $this->assertEquals('http://bar/foo', $link->href);
        $this->assertEquals(new Meta(['foo' => 'bar']), $link->meta);
    }

    public function testSetLink()
    {
        $link = new Link(['href' => 'http://foo/bar']);
        $links = new Links(['self' => $link]);
        $this->assertSame($link, $links->self);
    }

    /**
     * @expectedException \Jck\JsonApiResponse\Exception\InvalidArgumentException
     */
    public function testSetWrongValue()
    {
        $links = new Links();
        $links->foo = new \stdClass();
    }

    public function testJsonSerialize()
    {
        $links = new Links(['self' => 'http://foo/bar', 'related' => ['href' => 'http://bar/foo', 'meta' => ['foo' => 'bar']]]);
        $this->assertEquals(['self' => 'http://foo/bar', 'related' => ['href' => 'http://bar/foo', 'meta' => ['foo' => 'bar']]], $links->jsonSerialize());

        $links->foo = 'http://bar.com/baz';
        $this->assertEquals(
            ['self' => 'http://foo/bar', 'related' => ['href' => 'http://bar/foo', 'meta' => ['foo' => 'bar']], 'foo' => 'http://bar.com/baz'],
            $links->jsonSerialize()
        );
    }

    public function testIsEmpty()
    {
        $links = new Links();
        $this->assertTrue($links->isEmpty());
        $links->foo = 'bar';
        $this->assertFalse($links->isEmpty());
    }
}
