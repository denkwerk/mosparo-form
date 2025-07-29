:navigation-title: NormalizedData

..  _api-normalized-data:

============================================
NormalizedData
============================================

..  php:namespace::  Denkwerk\MosparoForm\Domain\Model\Dto

..  php:class:: NormalizedData

    A data container class holding normalized $_POST data for mosparo validation.

    ..  php:method:: setFormData(array $formData)

        Set the list of form data fields.

        :param array<string, mixed> $formData: The form data.
        :returns: void

    ..  php:method:: getFormData()

        Get the list of form data fields.

        :returns: Normalized $_POST data as an array<string, mixed> ready to be validated by mosparo, or an empty array if not applicable.

    ..  php:method:: addFormData(string $key, mixed $value)

        Add a field to the form data.

        :param string $key: The field key.
        :param mixed $value: The field value.
        :returns: void

    ..  php:method:: setRequiredFields(array $requiredFields)

        Set the list of required fields.

        :param array<string> $requiredFields: The list of required fields.
        :returns: void

    ..  php:method:: getRequiredFields()

        Get the list of required fields.

        :returns: An array of field names that must be validated by mosparo.

    ..  php:method:: addRequiredField(string $value)

        Add a field to the required fields list.

        :param string $value: The field key.
        :returns: void

    ..  php:method:: setVerifiableFields(array $verifiableFields)

        Set the list of verifiable fields.

        :param array<string> $verifiableFields: The verifiable field names.
        :returns: void

    ..  php:method:: getVerifiableFields()

        Get the list of verifiable fields.

        :returns: An array of field names that are eligible for mosparo validation.

    ..  php:method:: addVerifiableField(string $value)

        Add a field to the verifiable fields list.

        :param string $value: The field name.
        :returns: void


