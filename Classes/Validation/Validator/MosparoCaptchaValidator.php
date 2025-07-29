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

namespace Denkwerk\MosparoForm\Validation\Validator;

use Denkwerk\MosparoForm\Domain\Model\Form\FormsMosparoFormDefinition;
use Denkwerk\MosparoForm\Domain\Model\Form\MosparoFormDefinitionInterface;
use Denkwerk\MosparoForm\FormNormalizer\FormNormalizerManager;
use Mosparo\ApiClient\Client;
use TYPO3\CMS\Core\Http\ServerRequest;
use TYPO3\CMS\Extbase\Validation\Validator\AbstractValidator;
use TYPO3\CMS\Form\Domain\Model\FormDefinition;
use TYPO3\CMS\Form\Domain\Model\FormElements\FormElementInterface;

/**
 * Class MosparoCaptchaValidator
 * @package Denkwerk\MosparoForm\Validation\Validator
 */
final class MosparoCaptchaValidator extends AbstractValidator
{
    protected $acceptsEmptyValues = false;

    public function __construct(
        protected FormNormalizerManager $formNormalizerManager,
    ) {
    }

    /**
     * @var array<string, array<int, string>>
     */
    protected $supportedOptions = [
        'selectedProject' => [
            null,
            'The mosparo project configuration to be used',
            'string'
        ],
        'requiredFields' => [
            null,
            'List of all required fields that should be validated by mosparo.
            This is to prevent disabled fields from being ignored by the frontend class "mosparo__ignored-field". Comma-separated list',
            'string'
        ],
        'verifiableFields' => [
            null,
            'List of all verifiable fields that should be validated by mosparo.
            This is to prevent disabled fields from being ignored by the frontend class "mosparo__ignored-field". Comma-separated list',
            'string'
        ],
        'formDefinition' => [
            null,
            'Instance implementing MosparoFormDefinitionInterface,
            providing the form structure needed for normalizing submitted data before verification.
            If empty, it will be checked whether this is a Form Framework form or fallback to Extbase handling.',
            MosparoFormDefinitionInterface::class,
            false
        ],
    ];

    /**
     * @param mixed $value
     */
    public function isValid(mixed $value): void
    {
        $configuration = [];
        /** @var string|null $selectedProject */
        $selectedProject = $this->options['selectedProject'] ?? null;
        /** @var MosparoFormDefinitionInterface|null $formDefinition */
        $formDefinition = $this->options['formDefinition'] ?? null;
        $formFrameworkFormDefinition = $GLOBALS['TYPO3_REQUEST']->getAttribute('mosparoFormDefinition');

        if (isset($GLOBALS['TYPO3_REQUEST']) === false ||
            !$GLOBALS['TYPO3_REQUEST'] instanceof ServerRequest
        ) {
            $this->addError('Mosparo Captcha Validator - Missing server request object', 1428031545);
            return;
        }

        // Retrieve $selectedProject by form field settings in form framework forms
        if ($formDefinition === null &&
            $formFrameworkFormDefinition instanceof FormDefinition
        ) {
            foreach ($formFrameworkFormDefinition->getElements() as $element) {
                if ($element instanceof FormElementInterface &&
                    $element->getType() === 'MosparoCaptcha'
                ) {
                    $properties = $element->getProperties();
                    if (!empty(trim($properties['selectedProject']))) {
                        $selectedProject = trim($properties['selectedProject']);
                        break;
                    }
                }
            }
        }

        $typoScriptSetupArray = $GLOBALS['TYPO3_REQUEST']->getAttribute('frontend.typoscript')->getSetupArray();

        // Use TypoScript setting “defaultProject” for selectedProject if the argument “selectedProject” is empty or does not exist
        if ($selectedProject === null ||
            isset($typoScriptSetupArray['plugin.']['tx_mosparoform.']['settings.']['projects.'][$selectedProject . '.']['publicServer']) === false
        ) {
            $selectedProject = strtolower($typoScriptSetupArray['plugin.']['tx_mosparoform.']['settings.']['defaultProject'] ?? 'default');
        }

        $configuration['verifyServer'] = rtrim($typoScriptSetupArray['plugin.']['tx_mosparoform.']['settings.']['projects.'][$selectedProject . '.']['verifyServer'] ?? '', '/');
        $configuration['publicKey'] = $typoScriptSetupArray['plugin.']['tx_mosparoform.']['settings.']['projects.'][$selectedProject . '.']['publicKey']  ?? '';
        $configuration['privateKey'] = $typoScriptSetupArray['plugin.']['tx_mosparoform.']['settings.']['projects.'][$selectedProject . '.']['privateKey'] ?? '';
        $configuration['auth'] = $typoScriptSetupArray['plugin.']['tx_mosparoform.']['settings.']['projects.'][$selectedProject . '.']['auth'] ?? null;

        if (
            $configuration['verifyServer'] === '' ||
            $configuration['publicKey'] === '' ||
            $configuration['privateKey'] === ''
        ) {
            $this->addError('Mosparo Captcha Validator - Missing config', 1427031933);
            return;
        }

        $client = new Client(
            $configuration['verifyServer'],
            $configuration['publicKey'],
            $configuration['privateKey'],
            [
                'verify' => false,
                'auth' => $configuration['auth'] ?? null,
            ]
        );

        if (isset($_POST['_mosparo_submitToken'], $_POST['_mosparo_validationToken']) === false) {
            $this->addError('Mosparo Captcha Validator - Missing validation token form fields', 1427031944);
            return;
        }

        $mosparoSubmitToken = $_POST['_mosparo_submitToken'];
        $mosparoValidationToken = $_POST['_mosparo_validationToken'];

        try {
            // mosparo expects the exact field keys as name i.e. "tx_motelonecontact_contact_form_motel_one[contact][topic]"
            // and all fields that were visible in the frontend for this request
            // To normalize these fields correctly, we must use the appropriate form normalizer.
            // If $formDefinition is already an instance of MosparoFormDefinitionInterface, we proceed with it and expect a supporting normalizer is registered.
            // If $formDefinition is null but the request attribute 'mosparoFormDefinition' exists,
            // we create a FormsMosparoFormDefinition instance for Form Framework form validation.
            // Otherwise, we fall back to the Extbase form normalizer.
            if (!$formDefinition instanceof MosparoFormDefinitionInterface) {
                if ($formFrameworkFormDefinition instanceof FormDefinition) {
                    $formDefinition = new FormsMosparoFormDefinition($formFrameworkFormDefinition);
                } else {
                    $formDefinition = null;
                }
            }
            $normalizedData = $this->formNormalizerManager->normalize(
                $_POST,
                $formDefinition
            );

            $result = $client->verifySubmission(
                $normalizedData->getFormData(),
                $mosparoSubmitToken,
                $mosparoValidationToken
            );

            if (!$result->isSubmittable()) {
                // Show error message
                $this->addError('Mosparo Captcha Validator - Some fields are not valid', 1427031929);
                return;
            }

            // Override RequiredFields and VerifiableFields by validator options
            if ($this->options['requiredFields'] !== null) {
                $normalizedData->setRequiredFields(array_filter(array_map('trim', explode(',', $this->options['requiredFields']))));
            }
            if ($this->options['verifiableFields'] !== null) {
                $normalizedData->setVerifiableFields(array_filter(array_map('trim', explode(',', $this->options['verifiableFields']))));
            }

            // We need to check whether all form fields have really been checked by mosparo.
            // See https://documentation.mosparo.io/docs/integration/ignored_fields and
            // https://documentation.mosparo.io/docs/integration/bypass_protection
            $verifiedFields = array_keys($result->getVerifiedFields());
            $requiredFieldDifference = array_diff($normalizedData->getRequiredFields(), $verifiedFields);
            $verifiableFieldDifference = array_diff($normalizedData->getVerifiableFields(), $verifiedFields);
            if (!empty($requiredFieldDifference) &&
                !empty($verifiableFieldDifference)
            ) {
                $this->addError('Mosparo Captcha Validator - Some fields were not evaluated', 1427031929);
                return;
            }
        } catch (\Mosparo\ApiClient\Exception $e) {
            $this->addError($e->getMessage(), 1427031944);
        }
    }
}
