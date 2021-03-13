<?php

declare(strict_types=1);

namespace dokuwiki\plugin\today\test;

use Doku_Handler;
use dokuwiki\test\mock\Doku_Renderer;
use syntax_plugin_today;
use DokuWikiTest;

/**
 * Tests for the syntax component of the today plugin
 *
 * @group plugin_today
 * @group plugins
 */
class SyntaxTest extends DokuWikiTest {
    protected $pluginsEnabled = ['today'];

    public static function parseMatchTestDataProvider () {
        return [
            [
                '{today name:space}',
                [
                    'namespace' => 'name:space',
                ],
                'simple example'
            ],
        ];
    }

    /**
     * @dataProvider parseMatchTestDataProvider
     *
     * @param $input
     * @param $expectedOutput
     * @param $msg
     */
    public function testParseMatch($input, $expectedOutput, $msg) {
        // arrange
        /** @var syntax_plugin_today $syntax */
        $syntax = plugin_load('syntax', 'today');

        // act
        $actualOutput = $syntax->handle($input, 5, 1, new Doku_Handler());

        // assert
        self::assertEquals($expectedOutput, $actualOutput, $msg);
    }

    public function testRendererXHTML() {
        /** @var syntax_plugin_today $syntax */
        $syntax = plugin_load('syntax', 'today');
        $testData = [
            'namespace' => 'name:space',
        ];
        $today = date('Y-m-d');

        $mockRenderer = $this->createMock(Doku_Renderer::class);
        $mockRenderer->expects(self::once())
            ->method('internallink')
            ->with($testData['namespace'] . ':' . $today, 'today');

        $actualStatus = $syntax->render('xhtml', $mockRenderer, $testData);

        self::assertTrue($actualStatus);
    }

    public function testRendererMeta() {
        /** @var syntax_plugin_today $syntax */
        $syntax = plugin_load('syntax', 'today');
        $testData = [
            'namespace' => 'name:space',
        ];
        $mockRenderer = $this->createMock(Doku_Renderer::class);
        $mockRenderer->expects(self::never())
            ->method('internallink');

        $actualStatus = $syntax->render('meta', $mockRenderer, $testData);

        self::assertFalse($actualStatus);
    }
}
