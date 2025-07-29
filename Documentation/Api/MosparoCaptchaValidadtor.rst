:navigation-title: MosparoCaptchaValidator

..  _api-mosparo-captcha-validator:

============================================
MosparoCaptchaValidator
============================================

..  php:namespace:: Denkwerk\MosparoForm\Validation\Validator

..  php:class:: MosparoCaptchaValidator

    Validator class that integrates mosparo verification into TYPO3 Extbase and Form Framework forms.

    Validates form submissions by sending normalized data to a mosparo server using the configured project credentials.

    Supported options:

    :option string selectedProject: The mosparo project configuration key to be used.
    :option string requiredFields: Comma-separated list of field names that must be verified.
    :option string verifiableFields: Comma-separated list of additional field names that should be checked.
    :option Denkwerk\MosparoForm\Domain\Model\Form\MosparoFormDefinitionInterface formDefinition: Used to extract and normalize form structure.

    ..  php:method:: __construct(FormNormalizerManager $formNormalizerManager)

        :param FormNormalizerManager $formNormalizerManager: The form normalizer manager service.

    ..  php:method:: isValid(mixed $value)

        Validates the submitted form data using mosparo.

        - Normalizes submitted form data via the registered normalizer.
        - Sends verification request to mosparo.
        - Checks that required and verifiable fields are included in the result.
        - Adds validation errors if verification fails or fields are missing.

        :param mixed $value: Not used; required by interface.
        :returns: void
