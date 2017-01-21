<?php
/**
 * Created by PhpStorm.
 * User: jeckel
 * Date: 21/01/17
 * Time: 17:17
 */

namespace Tests\Jeckel\JsonApiResponse\JsonApiResponse;


use Jeckel\JsonApiResponse\Links;
use Jeckel\JsonApiResponse\Meta;
use Jeckel\JsonApiResponse\Resource;
use Jeckel\JsonApiResponse\SingleDocument;


class SingleDocumentTest extends \PHPUnit_Framework_TestCase
{
    public function testConstruct()
    {
        $document = new SingleDocument();
        $this->assertInstanceOf(Resource::class, $document->data);
        $this->assertInstanceOf(Links::class, $document->links);
        $this->assertInstanceOf(Meta::class, $document->meta);
        $this->assertTrue($document->isEmpty());
        $this->assertFalse($document->isValid());

    }

    public function testWithData()
    {
        $resourceInvalid = $this->createMock(Resource::class);
        $resourceInvalid->expects($this->any())->method('isValid')->willReturn(false);

        $document = new SingleDocument(['data' => $resourceInvalid]);
        $this->assertSame($resourceInvalid, $document->data);
        $this->assertFalse($document->isEmpty());
        $this->assertFalse($document->isValid());

        $resourceValid = $this->createMock(Resource::class);
        $resourceValid->expects($this->any())->method('isValid')->willReturn(true);
        $resourceValid->expects($this->any())->method('jsonSerialize')->willReturn(['foo' => 'bar']);
        $document->data = $resourceValid;
        $this->assertSame($resourceValid, $document->data);
        $this->assertFalse($document->isEmpty());
        $this->assertTrue($document->isValid());
        $this->assertEquals(['data' => ['foo' => 'bar']], $document->jsonSerialize());
    }

    /**
     * @depends testWithData
     */
    public function testWithLinks()
    {
        $resource = $this->createMock(Resource::class);
        $resource->expects($this->any())->method('isValid')->willReturn(true);
        $resource->expects($this->any())->method('jsonSerialize')->willReturn(['foo' => 'bar']);

        $linksInvalid = $this->createMock(Links::class);
        $linksInvalid->expects($this->any())->method('isValid')->willReturn(false);

        $document = new SingleDocument(['data' => $resource, 'links' => $linksInvalid]);
        $this->assertSame($linksInvalid, $document->links);
        $this->assertFalse($document->isEmpty());
        $this->assertFalse($document->isValid());

        $linksValid = $this->createMock(Links::class);
        $linksValid->expects($this->any())->method('isValid')->willReturn(true);
        $linksValid->expects($this->any())->method('jsonSerialize')->willReturn(['self' => 'http://foo.com/bar']);
        $document->links = $linksValid;

        $this->assertSame($linksValid, $document->links);
        $this->assertFalse($document->isEmpty());
        $this->asserttrue($document->isValid());
        $this->assertEquals(['data' => ['foo' => 'bar'], 'links' => ['self' => 'http://foo.com/bar']], $document->jsonSerialize());
    }

    public function testWithMeta()
    {
        $resource = $this->createMock(Resource::class);
        $resource->expects($this->any())->method('isValid')->willReturn(true);
        $resource->expects($this->any())->method('jsonSerialize')->willReturn(['foo' => 'bar']);

        $metaInvalid = $this->createMock(Meta::class);
        $metaInvalid->expects($this->any())->method('isValid')->willReturn(false);

        $document = new SingleDocument(['data' => $resource, 'meta' => $metaInvalid]);
        $this->assertSame($metaInvalid, $document->meta);
        $this->assertFalse($document->isEmpty());
        $this->assertFalse($document->isValid());

        $metaValid = $this->createMock(Meta::class);
        $metaValid->expects($this->any())->method('isValid')->willReturn(true);
        $metaValid->expects($this->any())->method('jsonSerialize')->willReturn(['bar' => 'baz']);

        $document->meta = $metaValid;

        $this->assertSame($metaValid, $document->meta);
        $this->assertFalse($document->isEmpty());
        $this->assertTrue($document->isValid());
        $this->assertEquals(['data' => ['foo' => 'bar'], 'meta' => ['bar' => 'baz']], $document->jsonSerialize());
    }

    public function testSetMetaFromArray()
    {
        $document = new SingleDocument(['meta' => ['foo' => 'bar']]);
        $this->assertInstanceOf(Meta::class, $document->meta);
        $this->assertEquals('bar', $document->meta->foo);
    }

    public function testSetLinksFromArray()
    {
        $document = new SingleDocument(['links' => ['self' => 'http://foo.com/bar']]);
        $this->assertInstanceOf(Links::class, $document->links);
        $this->assertEquals('http://foo.com/bar', $document->links->self);
    }

    public function testSetDataFromArray()
    {
        $document = new SingleDocument(['data' => ['id' => 'foo', 'type' => 'bar']]);
        $this->assertInstanceOf(Resource::class, $document->data);
        $this->assertEquals('foo', $document->data->id);
        $this->assertEquals('bar', $document->data->type);
    }

    /**
     * @expectedException \Jeckel\JsonApiResponse\Exception\InvalidArgumentException
     */
    public function testSetInvalidKey()
    {
        new SingleDocument(['foo' => 'bar']);
    }

    /**
     * @expectedException \Jeckel\JsonApiResponse\Exception\InvalidArgumentException
     */
    public function testSetInvalidMeta()
    {
        new SingleDocument(['meta' => new \stdClass()]);
    }

    /**
     * @expectedException \Jeckel\JsonApiResponse\Exception\InvalidArgumentException
     */
    public function testSetInvalidLinks()
    {
        new SingleDocument(['links' => new \stdClass()]);
    }

    /**
     * @expectedException \Jeckel\JsonApiResponse\Exception\InvalidArgumentException
     */
    public function testSetInvalidData()
    {
        new SingleDocument(['data' => new \stdClass()]);
    }
}