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

namespace Denkwerk\MosparoForm\FormNormalizer;

use Denkwerk\MosparoForm\Domain\Model\Dto\NormalizedData;
use Denkwerk\MosparoForm\Domain\Model\Form\FormsMosparoFormDefinition;
use Denkwerk\MosparoForm\Domain\Model\Form\MosparoFormDefinitionInterface;
use Denkwerk\MosparoForm\Event\ModifyFormFieldTypeListsEvent;
use Psr\EventDispatcher\EventDispatcherInterface;
use TYPO3\CMS\Extbase\Validation\Validator\NotEmptyValidator;
use TYPO3\CMS\Form\Domain\Model\FormElements\FormElementInterface;

/**
 * Class FormNormalizer
 * @package Denkwerk\MosparoForm\FormNormalizer
 */
class FormNormalizer implements FormNormalizerInterface
{
    /**
     * @var string[]
     */
    protected array $ignoredFieldTypes = [
        'MosparoCaptcha',
        'Page',
        'SummaryPage',
        'Password',
        'AdvancedPassword',
        'Checkbox',
        'MultiCheckbox',
        'RadioButton',
        'FileUpload',
        'ImageUpload',
        'Hidden',
        'StaticText',
        'ContentElement',
    ];

    /**
     * @var string[]
     */
    protected array $verifiableFieldTypes = [
        'Text',
        'Textarea',
        'Email',
        'Telephone',
        'Url',
        'Number',
        'Date',
        'DatePicker',
        'SingleSelect',
        'MultiSelect',
        'CountrySelect',
        'Honeypot',
    ];

    public function __construct(
        protected readonly EventDispatcherInterface $eventDispatcher,
    ) {
        $this->initializeFieldTypesWithEvent();
    }

    /**
     * Allow other extensions to modify the ignored and verifiable field type lists
     *
     * @return void
     */
    protected function initializeFieldTypesWithEvent(): void
    {
        // Allow other extensions to modify the ignored and verifiable field type lists
        $event = new ModifyFormFieldTypeListsEvent(
            $this->ignoredFieldTypes,
            $this->verifiableFieldTypes
        );
        $this->eventDispatcher->dispatch($event);

        $this->ignoredFieldTypes = $event->getIgnoredFieldTypes();
        $this->verifiableFieldTypes = $event->getVerifiableFieldTypes();
    }

    /**
     * @param MosparoFormDefinitionInterface|null $formDefinition
     * @return bool
     */
    public function supports(?MosparoFormDefinitionInterface $formDefinition): bool
    {
        return $formDefinition instanceof FormsMosparoFormDefinition;
    }

    /**
     * This function converts $_POST data into a structured object for mosparo backend verification and
     * provides the $requiredFields and $verifiableFields list to confirm that all fields were verified
     * in the frontend request
     * This function is used for all requests that used TYPO3 Form Framework
     *
     * @param array<int|string, mixed> $postData The $_POST variable value
     * @param MosparoFormDefinitionInterface|null $formDefinition
     * @return NormalizedData
     */
    public function normalize(array $postData, ?MosparoFormDefinitionInterface $formDefinition): NormalizedData
    {
        $data = new NormalizedData();

        if ($formDefinition instanceof FormsMosparoFormDefinition &&
            isset($postData['tx_form_formframework'][$formDefinition->getFormFrameworkDefinition()->getIdentifier()]) &&
            is_array($postData['tx_form_formframework'][$formDefinition->getFormFrameworkDefinition()->getIdentifier()]) &&
            count($postData['tx_form_formframework'][$formDefinition->getFormFrameworkDefinition()->getIdentifier()]) > 0
        ) {
            foreach ($postData['tx_form_formframework'][$formDefinition->getFormFrameworkDefinition()->getIdentifier()] as $formElementIdentifier => $value) {
                $formElement = $formDefinition->getFormFrameworkDefinition()->getElementByIdentifier((string)$formElementIdentifier);
                $key = 'tx_form_formframework[' . $formDefinition->getFormFrameworkDefinition()->getIdentifier() . '][' . (string)$formElementIdentifier . ']';

                // Just add core fields like "__state" or "__currentPage".
                // In some cases, the session data associated with the honeypot field is not available,
                // resulting in a form submission that bypasses the honeypot validation.
                // Consequently, the honeypot field is not present in the $formDefinition,
                // resulting in a failed mosparo validation if it is not added here.
                if (!$formElement instanceof FormElementInterface) {
                    $data->addFormData($key, $value);
                    continue;
                }

                if (in_array($formElement->getType(), $this->ignoredFieldTypes, true)) {
                    continue;
                }

                // For multi-value elements, mosparo expects an array type, but we get a string on empty selects
                if ($formElement->getType() === 'MultiSelect' &&
                    empty($value)
                ) {
                    $value = [];
                }

                $data->addFormData($key, $value);

                if ($this->isRequired($formElement)) {
                    $data->addRequiredField($key);
                }

                if (in_array($formElement->getType(), $this->verifiableFieldTypes, true)) {
                    $data->addVerifiableField($key);
                }
            }
        }

        return $data;
    }

    /**
     * Checks if a form element has the 'NotEmpty' validator set
     *
     * @param FormElementInterface $formElement
     * @return bool
     */
    private function isRequired(FormElementInterface $formElement): bool
    {
        foreach ($formElement->getValidators() as $validator) {
            if ($validator instanceof NotEmptyValidator) {
                return true;
            }
        }
        return false;
    }
}
