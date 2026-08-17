<?php 

declare(strict_types=1);

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/secrets.php';

use Minishlink\WebPush\WebPush;
use Minishlink\WebPush\Subscription;
use Minishlink\WebPush\VAPID;

/**
 * Read message from CLI.
 * 
 * Example:
 * php send.php "Ich denke an dich"
 */

$message = $argv[1] ?? null;

if($message === null) {
    echo "Usage: php send.php \"message\"\n";
    exit(1);
}

/**
 * Load push subscription
 */

$subscriptionPath = __DIR__ . '/subscription.json';

if (!file_exists($subscriptionPath)) {
    echo "subscription.json not found. \n";
    exit(1);
}

$subscriptionData = json_decode(file_get_contents($subscriptionPath), true);

if ($subscriptionData === null) {
    echo "Invalid subscription.json.\n";
    exit(1);
}


/**
 * Create subscription object
 */

$subscription = Subscription::create($subscriptionData);


/**
 * Configuriere Web Push authentication.
 */

$webPush = new WebPush(
    [
        'VAPID' => [
            'subject' => 'https://backofenlachs.github.io/emmi-love-app/',
            'publicKey' => VAPID_PUBLIC_KEY,
            'privateKey' => VAPID_PRIVATE_KEY
        ]
    ],
    [
        'TLL' => 3600,
        'urgency' => 'normal',
        'contentType' => 'application/json'
    ]
);

/**
 * Payload received by the service worker
 */

$payload = json_encode(
    [
        'title' => 'Für Emmi ❤️',
        'body' => $message
    ],
    JSON_UNESCAPED_UNICODE
);

/**
 * Send notification.
 */

$report = $webPush->sendOneNotification(
    $subscription,
    $payload
);

if($report->isSuccess()) {
    echo "Push send successfully.\n";
    exit(0);
}

echo "push send failed: \n";
echo $report->getReason() . PHP_EOL;

exit(1);