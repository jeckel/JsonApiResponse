<?php
/**
 * Created by PhpStorm.
 * User: jeckel
 * Date: 21/01/17
 * Time: 10:39
 */

namespace Tests\Jeckel\JsonApiResponse\functional;

use Jeckel\JsonApiResponse\SingleDocument;

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
}
