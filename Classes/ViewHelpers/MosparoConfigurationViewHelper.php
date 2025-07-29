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

namespace Denkwerk\MosparoForm\ViewHelpers;

use TYPO3\CMS\Core\Http\ServerRequest;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * ViewHelper to retrieve the public mosparo project configuration from the TypoScript setup.
 *
 * This ViewHelper returns configuration values (`publicServer`, `uuid`, `publicKey`) for a specific
 * mosparo project defined in TypoScript. The project can be specified via the `selectedProject` argument.
 * If no project is specified, or the given project is not configured in TypoScript, the configuration
 * of the `defaultProject` (as defined in TypoScript) will be used instead.
 *
 * Example usage in a Fluid template:
 * <mosparoform:mosparoConfiguration selectedProject="projectA" />
 *
 * Expected TypoScript structure:
 * plugin.tx_mosparoform.settings.projects {
 *      defaultProject = projectA
 *      projectA {
 *          publicServer = https://...
 *          verifyServer = https://...
 *          uuid = ...
 *          publicKey = ...
 *          privateKey = ...
 *      }
 * }
 *
 * Class MosparoConfigurationViewHelper
 * @package Denkwerk\MosparoForm\ViewHelpers
 */
class MosparoConfigurationViewHelper extends AbstractViewHelper
{
    public function initializeArguments(): void
    {
        $this->registerArgument(
            'selectedProject',
            'string',
            'The mosparo project configuration to be used'
        );
    }

    /**
     * @return array{
     *     publicServer?: string,
     *     uuid?: string,
     *     publicKey?: string
     * }
     */
    public function render(): array
    {
        $configuration = [];
        $selectedProject = $this->arguments['selectedProject'];

        if (isset($GLOBALS['TYPO3_REQUEST']) &&
            $GLOBALS['TYPO3_REQUEST'] instanceof ServerRequest &&
            $GLOBALS['TYPO3_REQUEST']->getAttribute('frontend.typoscript')->hasSetup() === true
        ) {
            $typoScriptSetupArray = $GLOBALS['TYPO3_REQUEST']->getAttribute('frontend.typoscript')->getSetupArray();

            // Use TypoScript setting “defaultProject” for selectedProject if the argument “selectedProject” is empty or does not exist
            if ($selectedProject === null ||
                isset($typoScriptSetupArray['plugin.']['tx_mosparoform.']['settings.']['projects.'][$selectedProject . '.']['publicServer']) === false
            ) {
                $selectedProject = strtolower($typoScriptSetupArray['plugin.']['tx_mosparoform.']['settings.']['defaultProject'] ?? 'default');
            }

            $configuration['publicServer'] = rtrim($typoScriptSetupArray['plugin.']['tx_mosparoform.']['settings.']['projects.'][$selectedProject . '.']['publicServer'] ?? '', '/');
            $configuration['uuid'] = $typoScriptSetupArray['plugin.']['tx_mosparoform.']['settings.']['projects.'][$selectedProject . '.']['uuid'] ?? '';
            $configuration['publicKey'] = $typoScriptSetupArray['plugin.']['tx_mosparoform.']['settings.']['projects.'][$selectedProject . '.']['publicKey']  ?? '';
        }

        return $configuration;
    }
}
