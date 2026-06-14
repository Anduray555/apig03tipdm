<?php
declare(strict_types=1);

return [
    'settings' => [
        'displayErrorDetails' => ($_ENV['APP_ENV'] ?? 'production') === 'local',
        'logErrors' => true,
        'logErrorDetails' => true,
    ],
];