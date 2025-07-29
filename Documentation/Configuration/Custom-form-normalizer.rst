:navigation-title: Form normalizer

.. _form-normalizer:

================================
Form Normalizer
================================

In order to verify form submissions correctly with mosparo, the form data must be structured
in a specific way. This includes:

* The field names (keys) must exactly match the :php:`name` attributes used in the frontend form,
  e.g. :php:`tx_contact_contact_form[contact][topic]`
* All visible and verifiable fields must be included to allow mosparo to confirm the integrity
  of the submission

Since different extensions or custom form setups store form data differently,
a dedicated :ref:`form normalizer <_api-form-normalizer-interface>` must be implemented to extract and prepare this data.

When the :ref:`_api-mosparo-captcha-validator` is executed,
it queries all registered normalizers (tagged as :php:`mosparo.form_normalizer`) in the order of registration
and uses the **first** normalizer whose :php:`supports(...)` method returns :php:`true`.

That normalizer is then responsible for transforming the raw :php:`$_POST` data into a structured
:ref:`_api-normalized-data` object. This includes assigning the verifiable and required fields,
which mosparo uses to verify whether the submitted data has been tampered with or is valid.

..  seealso::
    | For more details, see the mosparo integration documentation:
    | `Performing verification <https://documentation.mosparo.io/docs/integration/custom#performing-verification>`__
    | `Prepare the form data <https://documentation.mosparo.io/docs/integration/custom#3-prepare-the-form-data>`__
    | `Ignored fields <https://documentation.mosparo.io/docs/integration/ignored_fields>`__
    | `Bypass protection <https://documentation.mosparo.io/docs/integration/bypass_protection>`__

..  seealso::
   For the full implementation, see the source code of the `mosparo Powermail <https://extensions.typo3.org/package/mahou/mosparo-powermail>`__ integration on GitHub: https://github.com/Digi92/mosparo-powermail

.. _form-normalizer-custom:

Create a custom form normalizer
================================
This section explains how to create and register a custom form normalizer so that it can be picked up by the :ref:`_api-mosparo-captcha-validator`.

#.  You need to implement the interface :ref:`_api-mosparo-form-definition-interface` to provide a wrapper around your specific form structure.

    ..  code-block:: php
        :caption: EXT:my_extension/Classes/Domain/Model/Form/MyCustomFormDefinition.php

        namespace Vendor\MyExtension\Domain\Model\Form;

        use Denkwerk\MosparoForm\Domain\Model\Form\MosparoFormDefinitionInterface;
        use Vendor\MyExtension\Domain\Model\Form\YourFormDefinition;

        class MyCustomFormDefinition implements MosparoFormDefinitionInterface
        {
           public function __construct(protected YourFormDefinition $yourFormDefinition)
           {
           }

           public function getYourFormDefinition(): YourForm
           {
               return $this->yourFormDefinition;
           }

           public function setYourFormDefinition(YourForm $yourFormDefinition): void
           {
               $this->yourFormDefinition = $yourFormDefinition;
           }
        }

#. Implement :ref:`_api-form-normalizer-interface` to create your form normalizer which handles your form structure accordingly.

   .. code-block:: php
      :caption: EXT:my_extension/Classes/FormNormalizer/MyCustomFormNormalizer.php

        namespace Vendor\MyExtension\FormNormalizer;

        use Vendor\MyExtension\Domain\Model\Form\MyCustomFormDefinition;
        use Denkwerk\MosparoForm\Domain\Model\Form\MosparoFormDefinitionInterface;
        use Denkwerk\MosparoForm\Domain\Model\Dto\NormalizedData;
        use Denkwerk\MosparoForm\FormNormalizer\FormNormalizerInterface;

        final class MyCustomFormNormalizer implements FormNormalizerInterface
        {
           protected array $ignoredFieldTypes = [
               'password',
               'submit',
               'reset',
               // add your ignored field types here
           ];

           protected array $verifiableFieldTypes = [
               'input',
               'textarea',
               'select',
               // add your verifiable field types here
           ];

           public function supports(?MosparoFormDefinitionInterface $formDefinition): bool
           {
               // Return true if this normalizer should handle the given form definition
               return $formDefinition instanceof MyCustomFormDefinition;
           }

           public function normalize(array $postData, MosparoFormDefinitionInterface $formDefinition): NormalizedData
           {
               // Your normalization logic here...
               $normalized = new NormalizedData();
               // e.g. filter, transform $postData...
               $normalized->setFormData($filteredData);
               return $normalized;
           }
        }

#. To register the normalizer service, add the following configuration to your custom provider extension :ref:`t3coreapi:extension-configuration-services-yaml` file:

   .. code-block:: yaml
      :caption: EXT:my_extension/Configuration/Services.yaml

      services:
         Vendor\MyExtension\FormNormalizer\MyCustomFormNormalizer:
           tags:
             - { name: 'mosparo.form_normalizer' }

#. | After implementing your own :ref:`_api-mosparo-form-definition-interface` and :ref:`_api-form-normalizer-interface`, you typically want to integrate mosparo spam protection in your form validation logic.
   | For example, in a Powermail spam shield class, the mosparo captcha validator can be used like this:

   .. code-block:: php
      :caption: EXT:mosparo-powermail/FormNormalizer/PowermailFormNormalizer.php

        use Denkwerk\MosparoForm\Validation\Validator\MosparoCaptchaValidator;
        use Vendor\Extension\Domain\Model\Form\PowermailMosparoFormDefinition;
        use TYPO3\CMS\Core\Utility\GeneralUtility;
        use TYPO3\CMS\Core\Configuration\Exception\ExtensionConfigurationExtensionNotConfiguredException;
        use TYPO3\CMS\Core\Configuration\Exception\ExtensionConfigurationPathDoesNotExistException;

        /**
         * mosparo spam protection
         *
         * @return bool
         * @throws ExtensionConfigurationExtensionNotConfiguredException
         * @throws ExtensionConfigurationPathDoesNotExistException
         */
        public function spamCheck(): bool
        {
            // Skip spam check if the mosparo field is not present or the check should be skipped
            if (!$this->hasMosparoField() || $this->shouldSkipMosaproValidatorCheck()) {
                return false;
            }

            /** @var MosparoCaptchaValidator $captchaValidator */
            $captchaValidator = GeneralUtility::makeInstance(MosparoCaptchaValidator::class);
            $captchaValidator->setOptions([
                'formDefinition' => new PowermailMosparoFormDefinition($this->mail->getForm()),
                'selectedProject' => $this->getSelectedProject(),
            ]);

            $captchaValidationResult = $captchaValidator->validate('');
            if (!$captchaValidationResult->hasErrors()) {
                // No spam detected
                return false;
            }

            // Spam detected
            return true;
        }

#. That's it! Your custom form setup is now seamlessly protected by mosparo, ensuring all relevant fields are verified server-side.
