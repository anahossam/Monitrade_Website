<?php
/**
 * Monitrade site configuration.
 * Copy this file to config/config.php and fill in your values.
 * config/config.php is ignored by git — never commit real credentials.
 */
return [
    // 'production' hides error details; 'development' shows them.
    'env' => 'production',

    // Public site address, no trailing slash. Leave the path empty when the
    // site lives at the domain root (e.g. https://monitrade.net).
    'base_url'  => 'https://monitrade.net',
    'base_path' => '',   // e.g. '/monitrade' if installed in a sub-folder

    'db' => [
        'host'     => 'localhost',
        'port'     => 3306,
        'name'     => 'cpaneluser_monitrade',
        'user'     => 'cpaneluser_monitrade',
        'password' => 'CHANGE_ME',
        'charset'  => 'utf8mb4',
    ],

    'mail' => [
        // Address the site sends from. Must be a mailbox on your domain,
        // otherwise most mail servers will reject or spam-folder it.
        'from'      => 'no-reply@monitrade.net',
        'from_name' => 'Monitrade Website',
        // Where contact / quote requests are delivered (comma-separated).
        // If empty, the "Company e-mail" from Admin → Settings is used.
        'to'        => '',
    ],
];
