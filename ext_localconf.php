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
});

// This hook will add the current FormDefinition to the "TYPO3_REQUEST", because we need the form definition in
// the MosparoCaptchaValidator to verify the validated fields.
// TODO:  Remove as soon as TYPO3 v13 support is discontinued,replaced by BeforeRenderableIsValidatedEventListener (v14+).
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['ext/form']['afterSubmit']['mosparo-form'] = \Denkwerk\MosparoForm\Hooks\FormElementHooks::class;
