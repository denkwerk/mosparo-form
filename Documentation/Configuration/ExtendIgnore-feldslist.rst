:navigation-title: Extending Field Type Lists (Form Framework)

.. _extending-field-type-lists-form-framework:

================================
Extending Field Type Lists in the FormNormalizer (Form Framework)
================================

This extension provides a FormNormalizer for the TYPO3 Form Framework that helps with processing submitted form data.
It allows specific field types to be ignored or verified during validation.

If you are using custom form field types, you need to register them via the event :php:`Denkwerk\MosparoForm\Event\ModifyFormFieldTypeListsEvent` to ensure proper validation behavior.

..  contents::
    :local:

..  seealso::
    | For details on how mosparo expects form data, see the official documentation:
    | https://documentation.mosparo.io/docs/integration/custom#preparing-form-data

..  note::
    | If you are using Extbase-based forms, this event is not needed.
    | In Extbase, you manually define which fields are passed to the validator, and control validation behavior via its options.
    | For detailed instructions on integrating mosparo with Extbase-based forms (<f:form>), see the corresponding documentation section: :ref:`_how-to-use-form-extbase`.


.. _extending-field-type-lists-form-framework-event:

ModifyFormFieldTypeListsEvent
================================
This event is triggered when the FormNormalizer is instantiated.

You can use this event to:

..  confval:: getIgnoredFieldTypes() / setIgnoredFieldTypes()
    :name: modify-form-field-type-lists-event-ignore-list
    :required: false
    :type: array of strings

    Add field types to the ignore list

..  confval:: getVerifiableFieldTypes() / setVerifiableFieldTypes()
    :name: modify-form-field-type-lists-event-verifiable-list
    :required: false
    :type: array of strings

    Add field types to the verifiable list


.. _extending-field-type-lists-form-framework-custom:

Adding your own Event Listener
================================
To register your own field types for the FormNormalizer, follow these steps:

#. In your custom provider extension, create a new PHP file at: :file:`Classes/EventListener/ModifyFormFieldTypesForFormListener.php`
#. | Add the following implementation.
   | This example adds custom field types to both the ignored and verifiable lists:

   ..  code-block:: php
       :caption: EXT:site_package/Classes/EventListener/ModifyFormFieldTypesForFormListener.php

        namespace Vendor\MyExtension\EventListener;

        use Denkwerk\MosparoForm\Event\ModifyFormFieldTypeListsEvent;

        final class ModifyFormFieldTypesForFormListener
        {
            public function __invoke(ModifyFormFieldTypeListsEvent $event): void
            {
                // Add custom field types to the ignore list
                $ignored = $event->getIgnoredFieldTypes();
                $ignored[] = 'customFormElementToIgnore';
                $event->setIgnoredFieldTypes($ignored);

                // Add custom field types to the verifiable list
                $verifiable = $event->getVerifiableFieldTypes();
                $verifiable[] = 'customFormInputElement';
                $event->setVerifiableFieldTypes($verifiable);
            }
        }
#. Register the listener in your custom provider extension :ref:`t3coreapi:extension-configuration-services-yaml`:

   ..  code-block:: yaml
       :caption: EXT:site_package/Configuration/service.yaml

        ...

        services:
          Vendor\MyExtension\EventListener\ModifyFormFieldTypesForFormListener:
            tags:
              - name: event.listener
                event: Denkwerk\MosparoForm\Event\ModifyFormFieldTypeListsEvent


