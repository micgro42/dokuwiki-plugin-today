<?php

declare(strict_types=1);

use dokuwiki\Extension\ActionPlugin;
use dokuwiki\Extension\EventHandler;

final class action_plugin_today extends ActionPlugin {
    public function register(EventHandler $controller): void
    {
        $controller->register_hook('ACTION_ACT_PREPROCESS', 'BEFORE', $this, 'redirectToTodayPage');
    }

    public function redirectToTodayPage(Doku_Event $event, ?array $param): void
    {
        if ($event->data === 'today') {
            $dateFormat = $this->getConf('dateformat');
            $today = date($dateFormat);
            $namespace = $this->getConf('namespace') ?? ':';
            send_redirect(wl("{$namespace}:{$today}"));
        }
    }
}
