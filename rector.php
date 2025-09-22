<?php

use Rector\Config\RectorConfig;
use Rector\TypeDeclaration\Rector\Property\TypedPropertyFromStrictConstructorRector;

return RectorConfig::configure()
    // paths to refactor
    ->withPaths([
        __DIR__ . '/app',
        __DIR__ . '/database',
        __DIR__ . '/routes',
        // __DIR__ . '/resources',
    ])
    // register single rule
    ->withRules([
        // TypedPropertyFromStrictConstructorRector::class,
    ])
    // here we can define, what prepared sets of rules will be applied
    ->withPreparedSets(
        deadCode: true,
        codeQuality: true,
        earlyReturn: true,
        typeDeclarations: true,
        strictBooleans: true,
        privatization: true,
    )
    // Skip the directories
    ->withSkip([
        __DIR__ . '/vendor',
    ]);
