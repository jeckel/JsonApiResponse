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
    }
}
