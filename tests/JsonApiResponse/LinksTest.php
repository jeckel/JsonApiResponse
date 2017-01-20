<?php
/**
 * User: Julien MERCIER <jeckel@jeckel.fr>
 * Date: 20/01/17
 * Time: 18:19
 */

namespace Tests\Jeckel\JsonApiResponse\JsonApiResponse;


use Jeckel\JsonApiResponse\Link;
use Jeckel\JsonApiResponse\Links;
use Jeckel\JsonApiResponse\Meta;


class LinksTest extends \PHPUnit_Framework_TestCase
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
     * @expectedException \Jeckel\JsonApiResponse\Exception\InvalidArgumentException
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
}
