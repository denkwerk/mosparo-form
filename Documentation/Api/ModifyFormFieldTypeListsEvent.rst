:navigation-title: ModifyFormFieldTypeListsEvent

..  _api-modify-form-field-type-lists-event:

============================================
ModifyFormFieldTypeListsEvent
============================================

.. php:namespace:: Denkwerk\MosparoForm\Event

.. php:class:: ModifyFormFieldTypeListsEvent

    This event allows extensions to modify which form field types should be ignored or considered verifiable by the Form Framework Normalizer (:file:`\Denkwerk\MosparoForm\FormNormalizer\FormNormalizer`).

    .. php:method:: __construct(array $ignoredFieldTypes, array $verifiableFieldTypes)

        Constructor.

        :param array<string> $ignoredFieldTypes: Initial list of field types to ignore.
        :param array<string> $verifiableFieldTypes: Initial list of field types to verify.

    .. php:method:: getIgnoredFieldTypes(): array

        Get the list of field types to be ignored by the FormNormalizer.

        :returntype: array<string>

    .. php:method:: setIgnoredFieldTypes(array $types): void

        Set the list of field types to ignore.

        :param array<string> $types: New list of field types to ignore.
        :returntype: void

    .. php:method:: getVerifiableFieldTypes(): array

        Get the list of field types that should be verified by the FormNormalizer.

        :returntype: array<string>

    .. php:method:: setVerifiableFieldTypes(array $types): void

        Set the list of field types that can be verified.

        :param array<string> $types: New list of field types to verify.
        :returntype: void
