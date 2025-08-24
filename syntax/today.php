<?php

declare(strict_types=1);

use dokuwiki\Extension\SyntaxPlugin;

/**
 * DokuWiki Plugin today (Syntax Component)
 *
 * @license GPL 2 http://www.gnu.org/licenses/gpl-2.0.html
 * @author  Michael Große <mic.grosse+dokuwiki@googlemail.com>
 */
final class syntax_plugin_today_today extends SyntaxPlugin
{
    /**
     * @return string Syntax mode type
     */
    public function getType(): string
    {
        return 'substition';
    }

    /**
     * @return string Paragraph type
     */
    public function getPType(): string
    {
        return 'normal';
    }

    /**
     * @return int Sort order - Low numbers go before high numbers
     */
    public function getSort(): int
    {
        return 1000;
    }

    /**
     * Connect lookup pattern to lexer.
     *
     * @param string $mode Parser mode
     */
    public function connectTo($mode): void
    {
        $this->Lexer->addSpecialPattern('\{today.*?\}', $mode, 'plugin_today_today');
    }

    /**
     * Handle matches of the today syntax
     *
     * @param string       $match   The match of the syntax
     * @param int          $state   The state of the handler
     * @param int          $pos     The position in the document
     * @param Doku_Handler $handler The handler
     *
     * @return array Data for the renderer
     */
    public function handle($match, $state, $pos, Doku_Handler $handler): array
    {
        $data = trim(substr($match, strlen('{today'), -1));
        $parts = explode(' ', $data);

        return [
            'namespace' => $parts[0] ?? null,
            'format' => $parts[1] ?? null,
        ];
    }

    /**
     * Render xhtml output or metadata
     *
     * @param string        $mode     Renderer mode (supported modes: xhtml)
     * @param Doku_Renderer $renderer The renderer
     * @param array         $data     The data from the handler() function
     *
     * @return bool If rendering was successful.
     */
    public function render($mode, Doku_Renderer $renderer, $data): bool
    {
        if ($mode !== 'xhtml') {
            return false;
        }

        $namespace = $data['namespace'] ?? '';
        $format = $data['format'] ?? 'Y-m-d';
        $today = date($format);
        $pageId = $namespace . ':' . $today;
        $renderer->internallink($pageId, 'today');

        return true;
    }
}
