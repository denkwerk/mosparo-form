<?php
declare(strict_types=1);

defined('TYPO3') or die();

call_user_func(function () {
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTypoScriptSetup(
        trim(
            '
            module.tx_form {
                settings {
                    yamlConfigurations {
                        1716293528 = EXT:mosparo_form/Configuration/Yaml/MosparoCaptchaFormField.yaml
                    }
                }
            }
        '
        )
    );

    $iconRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Imaging\IconRegistry::class);
    $iconRegistry->registerIcon(
        't3-mosparo-form',
        \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
        ['source' => 'EXT:mosparo_form/Resources/Public/Icons/Extension.svg']
    );
});

// This hook will add the current FormDefinition to the "TYPO3_REQUEST", because we need the form definition in
// the MosparoCaptchaValidator to verify the validated fields.
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['ext/form']['afterSubmit']['mosparo-form'] = \Denkwerk\MosparoForm\Hooks\FormElementHooks::class;
