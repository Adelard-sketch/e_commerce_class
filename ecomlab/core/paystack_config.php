<?php

function paystack_config()
{
    static $config = null;
    if ($config !== null) {
        return $config;
    }

    $config = [];
    $credentialsFile = __DIR__ . '/paystack_credentials.php';
    if (is_file($credentialsFile)) {
        $loadedConfig = require $credentialsFile;
        if (!is_array($loadedConfig)) {
            throw new RuntimeException('Paystack credentials file must return a PHP array.');
        }
        $config = $loadedConfig;
    }

    return $config;
}
