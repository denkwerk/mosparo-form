:navigation-title: FAQ

..  _faq:

================================
Frequently Asked Questions (FAQ)
================================

..  accordion::
    :name: faq

    ..  accordion-item:: How can I install this extension?
        :name: installation
        :header-level: 2
        :show:

        See chapter :ref:`installation`.

    ..  accordion-item:: How to can I include the TypoScript?
        :name: configuration
        :header-level: 2

        See chapter :ref:`_include-into-your-project`.

    ..  accordion-item:: How to use mosparo in a Form Framework form?
        :name: how-to-use-form-framework
        :header-level: 2

        See chapter :ref:`_how-to-use-form-framework`.

    ..  accordion-item:: How to use mosparo in a Extbase forms <f:form>?
        :name: how-to-use-form-extbase
        :header-level: 2

        See chapter :ref:`_how-to-use-form-extbase`.

    ..  accordion-item:: How to register a custom form for mosparo validation?
        :name: custom-form-normalizer
        :header-level: 2

        If your form setup is not supported by default, you can write a custom form normalizer to prepare the submitted data for mosparo validation.

        See chapter :ref:`_form-normalizer-custom` for step-by-step instructions.

    ..  accordion-item:: Does mosparo validation work with multi-step forms?
        :name: multistep-form
        :header-level: 2

        Currently, mosparo validation only works in multi-step forms if all fields to be validated are located on the same step as the mosparo form element.
        If the fields are spread across multiple steps, validation will not be triggered correctly.

        | This limitation is known and currently being worked on. See the related issue on GitHub:
        | https://github.com/mosparo/mosparo/issues/343

    ..  accordion-item:: Where to get help?
        :name: help
        :header-level: 2

        See chapter :ref:`help`.
