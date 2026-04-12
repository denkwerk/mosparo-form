<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'denkwerk - mosparo integration for EXT:form and Extbase-based forms',
    'description' => 'Integrates the mosparo spam protection system into TYPO3 EXT:form and Extbase forms, allowing easy and effective spam protection across different form implementations.',
    'category' => 'plugin',
    'state' => 'stable',
    'author' => 'denkwerk GmbH',
    'author_email' => 'hello@denkwerk.com',
    'version' => '1.0.4',
    'constraints' => [
        'depends' => [
            'php' => '8.2.0-8.5.99',
            'typo3' => '13.4.0-14.2.99',
            'forms' => '13.4.0-14.2.99',
            'mosparo/php-api-client' => '1.1.0-1.1.99'
        ],
        'conflicts' => [],
        'suggests' => [
            'mahou/mosparo-powermail' => ''
        ],
    ],
];
