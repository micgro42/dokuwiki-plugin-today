<?php

declare(strict_types=1);

namespace dokuwiki\plugin\today\test;

use Doku_Handler;
use dokuwiki\test\mock\Doku_Renderer;
use syntax_plugin_today_today;
use DokuWikiTest;

/**
 * Tests for the `{today name:space}` syntax component of the today plugin
 *
 * @group plugin_today
 * @group plugins
 */
final class SyntaxTodayTest extends DokuWikiTest {
    protected $pluginsEnabled = ['today'];

    public static function parseMatchTestDataProvider(): iterable {
        yield 'no format' =>
            [
                '{today name:space}',
                [
                    'namespace' => 'name:space',
                    'format' => null,
                ],
                'name:space:' . date('Y-m-d'),
            ];

        yield 'custom format' => [
            '{today name:space Y:Y-m-d}',
            [
                'namespace' => 'name:space',
                'format' => 'Y:Y-m-d',
            ],
            'name:space:' . date('Y:Y-m-d')
        ];
    }

    /**
     * @dataProvider parseMatchTestDataProvider
     */
    public function testParseMatch(string $input, array $expectedPluginInstructionData, string $expectedPageId): void {
        /** @var syntax_plugin_today_today $syntax */
        $syntax = plugin_load('syntax', 'today_today');

        $actualPluginInstructionData = $syntax->handle($input, 5, 1, new Doku_Handler());

        self::assertEquals($expectedPluginInstructionData, $actualPluginInstructionData);

        $mockRenderer = $this->createMock(Doku_Renderer::class);
        $mockRenderer->expects(self::once())
            ->method('internallink')
            ->with($expectedPageId, 'today');

        $actualStatus = $syntax->render('xhtml', $mockRenderer, $actualPluginInstructionData);

        self::assertTrue($actualStatus);
    }

    public function testRendererMeta(): void {
        /** @var syntax_plugin_today_today $syntax */
        $syntax = plugin_load('syntax', 'today_today');
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
