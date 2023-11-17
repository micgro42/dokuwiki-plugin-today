<?php

declare(strict_types=1);

use dokuwiki\Extension\ActionPlugin;
use dokuwiki\Extension\Event;
use dokuwiki\Extension\EventHandler;

final class action_plugin_today extends ActionPlugin
{
    public function register(EventHandler $controller): void
    {
        $controller->register_hook('ACTION_ACT_PREPROCESS', 'BEFORE', $this, 'redirectToTodayPage');
    }

    public function redirectToTodayPage(Event $event, ?array $param): void
    {
        if ($event->data === 'today') {
            global $INPUT;
            $namespace = $INPUT->str('namespace') ?? '';
            $today = date('Y-m-d');
            send_redirect(wl("{$namespace}:{$today}"));
        }
    }
}
