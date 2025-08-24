<?php

declare(strict_types=1);

namespace dokuwiki\plugin\today\test;

use DokuWikiTest;
use TestRequest;

/**
 * Tests for the `?do=today&namespace=foo:bar` action component of the today plugin
 *
 * @group plugin_today
 * @group plugins
 */
final class ActionTest extends DokuWikiTest {
    protected $pluginsEnabled = ['today'];

    public static function dataProvider(): iterable
    {
        yield 'no format' => [
            'extraParams' => [
                'namespace' => 'foo:bar',
            ],
            'expectedUrl' => '/doku.php?id=foo:bar:' . date('Y-m-d'),
        ];

        yield 'with format' => [
            'extraParams' => [
                'namespace' => 'foo:bar',
                'format' => 'Y:Y-m-d',
            ],
            'expectedUrl' => '/doku.php?id=foo:bar:' . date('Y:Y-m-d'),
        ];

        yield 'weekly' => [
            'extraParams' => [
                'namespace' => 'journal:weekly',
                'format' => 'Y:W',
            ],
            'expectedUrl' => '/doku.php?id=journal:weekly:' . date('Y:W'),
        ];
    }

    /**
     * @dataProvider dataProvider
     */
    public function testRedirect(array $extraParams, $expectedUrl): void
    {
        $request = new TestRequest();
        $response = $request->get( array_merge(['do' => 'today'], $extraParams) );

        $actualUrl = $response->getData('send_redirect')[0];

        $this->assertSame( $expectedUrl, $actualUrl);
    }
}
