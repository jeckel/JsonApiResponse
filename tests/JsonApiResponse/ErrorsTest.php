<?php
/**
 * Created by PhpStorm.
 * User: jmercier
 * Date: 24/01/17
 * Time: 16:23
 */
namespace Tests\Jck\JsonApiResponse\JsonApiResponse;

use Jck\JsonApiResponse\Error;
use Jck\JsonApiResponse\Errors;

class ErrorsTest extends \PHPUnit_Framework_TestCase
{
    public function testConstruct()
    {
        $error = new Errors();
        $this->assertTrue($error->isEmpty());
        $this->assertTrue($error->isValid());
    }

    public function testWithData()
    {
        $errorInvalid = $this->createMock(Error::class);
        $errorInvalid->method('isValid')->willReturn(false);

        $errors = new Errors([$errorInvalid]);
        $this->assertSame($errorInvalid, $errors[0]);
        $this->assertFalse($errors->isEmpty());
        $this->assertFalse($errors->isValid());

        $errorValid = $this->createMock(Error::class);
        $errorValid->method('isValid')->willReturn(true);
        $errorValid->method('jsonSerialize')->willReturn(['title' => 'foo']);
        $errors->foo = $errorValid;

        $this->assertSame($errorValid, $errors['foo']);
        $this->assertFalse($errors->isEmpty());
        $this->assertFalse($errors->isValid());

        $errorValidBis = $this->createMock(Error::class);
        $errorValidBis->method('isValid')->willReturn(true);
        $errorValidBis->method('jsonSerialize')->willReturn(['title' => 'bar']);
        $errors[0] = $errorValidBis;
        $this->assertSame($errorValidBis, $errors[0]);
        $this->assertFalse($errors->isEmpty());
        $this->assertTrue($errors->isValid());

        $expected = [['title' => 'bar'], ['title' => 'foo']];
        $this->assertEquals($expected, $errors->jsonSerialize());
    }

    /**
     * @expectedException \Jck\JsonApiResponse\Exception\InvalidArgumentException
     */
    public function testSetErrorWrongType()
    {
        new Error([new \stdClass()]);
    }
}
