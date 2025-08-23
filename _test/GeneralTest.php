<?php

declare(strict_types=1);

namespace dokuwiki\plugin\today\test;

use DokuWikiTest;

/**
 * General tests for the today plugin
 *
 * @group plugin_today
 * @group plugins
 */
final class GeneralTest extends DokuWikiTest
{

    /**
     * Simple test to make sure the plugin.info.txt is in correct format
     */
    public function testPluginInfo(): void
    {
        $file = __DIR__ . '/../plugin.info.txt';
        self::assertFileExists($file);

        $info = confToHash($file);

        self::assertArrayHasKey('base', $info);
        self::assertArrayHasKey('author', $info);
        self::assertArrayHasKey('email', $info);
        self::assertArrayHasKey('date', $info);
        self::assertArrayHasKey('name', $info);
        self::assertArrayHasKey('desc', $info);
        self::assertArrayHasKey('url', $info);

        self::assertEquals('today', $info[ 'base' ]);
        self::assertMatchesRegularExpression('/^https?:\/\//', $info[ 'url' ]);
        self::assertTrue(mail_isvalid($info[ 'email' ]));
        self::assertMatchesRegularExpression('/^\d\d\d\d-\d\d-\d\d$/', $info[ 'date' ]);
        self::assertTrue(false !== strtotime($info[ 'date' ]));
    }

    /**
     * Test to ensure that every conf['...'] entry in conf/default.php has a corresponding meta['...'] entry in
     * conf/metadata.php.
     */
    public function testPluginConf(): void
    {
        $conf_file = __DIR__ . '/../conf/default.php';
        $meta_file = __DIR__ . '/../conf/metadata.php';

        if (!file_exists($conf_file) && !file_exists($meta_file)) {
            self::markTestSkipped('No config files exist -> skipping test');
        }

        if (file_exists($conf_file)) {
            include($conf_file);
        }
        if (file_exists($meta_file)) {
            include($meta_file);
        }

        self::assertEquals(
            gettype($conf),
            gettype($meta),
            'Both ' . DOKU_PLUGIN . 'today/conf/default.php and ' . DOKU_PLUGIN . 'today/conf/metadata.php have to exist and contain the same keys.'
        );

        if ($conf !== null && $meta !== null) {
            foreach ($conf as $key => $value) {
                self::assertArrayHasKey(
                    $key,
                    $meta,
                    'Key $meta[\'' . $key . '\'] missing in ' . DOKU_PLUGIN . 'today/conf/metadata.php'
                );
            }

            foreach ($meta as $key => $value) {
                self::assertArrayHasKey(
                    $key,
                    $conf,
                    'Key $conf[\'' . $key . '\'] missing in ' . DOKU_PLUGIN . 'today/conf/default.php'
                );
            }
        }
    }
}
