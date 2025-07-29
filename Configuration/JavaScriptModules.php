<?php
declare(strict_types=1);

return [
    'dependencies' => [
        'core',
        'backend',
        'form'
    ],
    'tags' => [
        'backend.form',
    ],
    'imports' => [
        '@denkwerk/mosparo-form/' => 'EXT:mosparo_form/Resources/Public/JavaScript/',
    ],
];
