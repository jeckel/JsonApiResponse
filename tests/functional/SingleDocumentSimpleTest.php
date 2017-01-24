<?php
/**
 * Created by PhpStorm.
 * User: jeckel
 * Date: 21/01/17
 * Time: 10:39
 */

namespace Tests\Jck\JsonApiResponse\functional;

use Jck\JsonApiResponse\SingleDocument;

class SingleDocumentSimpleTest extends \PHPUnit_Framework_TestCase
{
    public function testFromArray()
    {
        $data = [
            'links' => [
                'self' => 'http://foo.com/bar/256'
            ],
            'data' => [
                'id' => '256',
                'type' => 'foo-doc-type',
                'attributes' => [
                    'foo' => 'bar',
                    'bar-bar' => [
                        'bar' => 'bar'
                    ]
                ]
            ]
        ];

        $document = new SingleDocument($data);
        $this->assertTrue($document->isValid());
        $this->assertJsonStringEqualsJsonFile(
            __DIR__ . '/fixtures/SingleDocumentSimple-FromArray.json',
            $document->toJson()
        );
    }

    public function testFromProperties()
    {
        $document = new SingleDocument();
        $document->links->self = 'http://foo.com/bar/256';
        $document->data->id = '256';
        $document->data->type = 'foo-doc-type';
        $document->data->attributes->foo = 'bar';
        $document->data->attributes['bar-bar'] = ['bar' => 'bar'];

        $this->assertTrue($document->isValid());
        $this->assertJsonStringEqualsJsonFile(
            __DIR__ . '/fixtures/SingleDocumentSimple-FromArray.json',
            $document->toJson()
        );
    }
}
