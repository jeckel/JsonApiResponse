<?php
/**
 * Created by PhpStorm.
 * User: jmercier
 * Date: 24/01/17
 * Time: 15:56
 */
namespace Tests\Jck\JsonApiResponse\JsonApiResponse;

use Jck\JsonApiResponse\Error;
use PHPUnit\Framework\TestCase;

class ErrorTest extends TestCase
{
    public function testConstruct()
    {
        $error = new Error();
        $this->assertTrue($error->isEmpty());
        $this->assertFalse($error->isValid());
    }

    public function testWithData()
    {
        $error = new Error(['status' => 404, 'title' => 'Not Found', 'detail' => 'Document is not found']);
        $this->assertEquals(404, $error->status);
        $this->assertEquals('Not Found', $error->title);
        $this->assertEquals('Document is not found', $error->detail);

        $error->status = 500;
        $error->title = 'Internal error';
        $error->detail = 'Error processing your request';
        $this->assertEquals(500, $error->status);
        $this->assertEquals('Internal error', $error->title);
        $this->assertEquals('Error processing your request', $error->detail);

        $expected = [
            'status' => 500,
            'title' => 'Internal error',
            'detail' => 'Error processing your request'
        ];
        $this->assertEquals($expected, $error->jsonSerialize());
    }

    /**
     * @expectedException \Jck\JsonApiResponse\Exception\InvalidArgumentException
     */
    public function testSetStatusWrongType()
    {
        new Error(['status' => 'foobar']);
    }

    /**
     * @expectedException \Jck\JsonApiResponse\Exception\InvalidArgumentException
     */
    public function testSetStatusTooLow()
    {
        new Error(['status' => 200]);
    }

    /**
     * @expectedException \Jck\JsonApiResponse\Exception\InvalidArgumentException
     */
    public function testSetStatusTooHigh()
    {
        new Error(['status' => 600]);
    }

    /**
     * @expectedException \Jck\JsonApiResponse\Exception\InvalidArgumentException
     */
    public function testSetTitleWrongType()
    {
        new Error(['title' => new \stdClass()]);
    }

    /**
     * @expectedException \Jck\JsonApiResponse\Exception\InvalidArgumentException
     */
    public function testSetDetailWrongType()
    {
        new Error(['detail' => new \stdClass()]);
    }

    /**
     * @expectedException \Jck\JsonApiResponse\Exception\InvalidArgumentException
     */
    public function testSetUnknownAttribute()
    {
        new Error(['foo' => 'bar']);
    }
}
