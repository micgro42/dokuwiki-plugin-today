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
     */
    public function testParseMatch(string $input, array $expectedOutput, string $msg): void {
        // arrange
        /** @var syntax_plugin_today_today $syntax */
        $syntax = plugin_load('syntax', 'today_today');

        // act
        $actualOutput = $syntax->handle($input, 5, 1, new Doku_Handler());

        // assert
        self::assertEquals($expectedOutput, $actualOutput, $msg);
    }

    public function testRendererXHTML(): void {
        /** @var syntax_plugin_today_today $syntax */
        $syntax = plugin_load('syntax', 'today_today');
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
