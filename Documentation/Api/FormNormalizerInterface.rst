:navigation-title: FormNormalizerInterface

..  _api-form-normalizer-interface:

============================================
FormNormalizerInterface
============================================

.. php:namespace:: Denkwerk\MosparoForm\FormNormalizer

.. php:interface:: FormNormalizerInterface

    Interface for normalizing form POST data into a `NormalizedData` object,
    used by mosparo for validation.

    .. php:method:: supports(?MosparoFormDefinitionInterface $formDefinition): bool

        Returns whether this normalizer supports processing the given form definition.

        :param MosparoFormDefinitionInterface|null $formDefinition: The form definition to check.
        :returntype: bool

    .. php:method:: normalize(array $postData, MosparoFormDefinitionInterface $formDefinition): NormalizedData

        Normalize the raw POST data according to the form definition and return a
        `NormalizedData` instance ready for mosparo validation.

        :param array<int|string, mixed> $postData: The raw `$_POST` data.
        :param MosparoFormDefinitionInterface $formDefinition: The form structure definition.
        :returntype: NormalizedData
