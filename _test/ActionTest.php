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

    public function testRedirect(): void
    {
        $request = new TestRequest();
        $response = $request->get(['do' => 'today', 'namespace' => 'foo:bar']);

        $actualUrl = $response->getData('send_redirect')[0];
        $expectedUrl = '/doku.php?id=foo:bar:' . date('Y-m-d');
        $this->assertSame( $expectedUrl, $actualUrl);
    }
}
