<?php

declare(strict_types=1);

/*
 * This file is part of the "mosparo-form" Extension for TYPO3 CMS.
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use TYPO3\CMS\Core\Security\ContentSecurityPolicy\Directive;
use TYPO3\CMS\Core\Security\ContentSecurityPolicy\Mutation;
use TYPO3\CMS\Core\Security\ContentSecurityPolicy\MutationCollection;
use TYPO3\CMS\Core\Security\ContentSecurityPolicy\MutationMode;
use TYPO3\CMS\Core\Security\ContentSecurityPolicy\RawValue;
use TYPO3\CMS\Core\Security\ContentSecurityPolicy\Scope;
use TYPO3\CMS\Core\Security\ContentSecurityPolicy\UriValue;
use TYPO3\CMS\Core\Site\SiteFinder;
use TYPO3\CMS\Core\Type\Map;
use TYPO3\CMS\Core\Utility\GeneralUtility;

$cspCollections = [];

// Collect Mosparo mutations for local servers from site settings
// Collect unique publicServer URLs to avoid duplicates
$mosparoLocalMutations = [];
$processedServers = [];
$sites = GeneralUtility::makeInstance(SiteFinder::class)->getAllSites(true);
foreach ($sites as $site) {
    $settings = $site->getSettings();
    
    // Get all projects from settings
    $projects = $settings->get('plugin.tx_mosparoform.settings.projects') ?? [];
    
    // Iterate through all projects
    if (is_array($projects)) {
        foreach ($projects as $projectName => $projectConfig) {
            if (!is_array($projectConfig)) {
                continue;
            }
            
            $publicServer = $projectConfig['publicServer'] ?? null;
            if ($publicServer !== null && $publicServer !== '' && !in_array($publicServer, $processedServers, true)) {
                $processedServers[] = $publicServer;
                
                $mosparoLocalMutations[] = new Mutation(
                    MutationMode::Extend,
                    Directive::ConnectSrc,
                    new UriValue($publicServer),
                );
                $mosparoLocalMutations[] = new Mutation(
                    MutationMode::Extend,
                    Directive::ScriptSrc,
                    new UriValue($publicServer),
                );
                $mosparoLocalMutations[] = new Mutation(
                    MutationMode::Extend,
                    Directive::ScriptSrcElem,
                    new UriValue($publicServer),
                    new RawValue('\'sha256-UzKAN4dj0lmfZDr4YbJxVQYdOTz2pFLRyhN7fkQCojM=\''),
                );
                $mosparoLocalMutations[] = new Mutation(
                    MutationMode::Extend,
                    Directive::StyleSrcElem,
                    new UriValue($publicServer),
                );
                $mosparoLocalMutations[] = new Mutation(
                    MutationMode::Extend,
                    Directive::ImgSrc,
                    new UriValue($publicServer),
                );
                $mosparoLocalMutations[] = new Mutation(
                    MutationMode::Extend,
                    Directive::FrameSrc,
                    new UriValue($publicServer),
                );
            }
        }
    }
}

// Create CSP collection for frontend with Mosparo mutations
// Always add public Mosparo server mutations, and local mutations if configured
$mosparoMutations = [
    // Mosparo-Mutationen from public server
    new Mutation(
        MutationMode::Extend,
        Directive::ConnectSrc,
        new UriValue('https://*.mosparo.io'),
    ),
    new Mutation(
        MutationMode::Extend,
        Directive::ScriptSrc,
        new UriValue('https://*.mosparo.io'),
    ),
    new Mutation(
        MutationMode::Extend,
        Directive::ScriptSrcElem,
        new UriValue('https://*.mosparo.io'),
    ),
    new Mutation(
        MutationMode::Extend,
        Directive::FrameSrc,
        new UriValue('https://*.mosparo.io'),
    ),
    // Mosparo-Mutationen from local server
    ...$mosparoLocalMutations
];

$cspCollection = new MutationCollection(...$mosparoMutations);
$cspCollections[] = [Scope::frontend(), $cspCollection];

if (count($cspCollections)) {
    return Map::fromEntries(...$cspCollections);
}

return new Map();

