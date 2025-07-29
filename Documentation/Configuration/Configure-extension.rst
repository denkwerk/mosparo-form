:navigation-title: Configure extension

================================
Configure extension
================================

..  contents::
    :local:

.. _configure-setup-mosparo:

..  tip::
    Before configuring the extension, make sure that mosparo is `set up <https://documentation.mosparo.io/docs/category/installation>`__ and a `project <https://documentation.mosparo.io/docs/usage/projects#create-a-project>`__ has been created.



.. _configure-via-site-set:

Configure the mosparo Form via site set settings (TYPO3 v13 and above)
================================

You can configure `projects <https://documentation.mosparo.io/docs/usage/projects>`_ and validation settings through the :ref:`Site settings editor <t3coreapi:site-settings-editor>` in the TYPO3 backend.

#. Got to module :guilabel:`Site Management > Settings` and edit your site configuration.
#. Under the category "mosparo Form" you can than enter your project configuration.

..  figure:: /_Images/site_settings_projects.png
    :alt: Site settings for mosparo forms
    :class: with-shadow
    :scale: 50
    Site settings for mosparo forms

.. _configure-via-site-set-env:

Environment variables in site settings
-----------------------------------------------

Site settings are defined in :file:`config/sites/my-site/settings.yaml`, where :ref:`environment variables <t3coreapi:environment-configuration>` can be used to provide the mosparo configuration.

..  code-block:: diff
    :caption: config/sites/my-site/settings.yaml (diff)

     plugin:
       tx_mosparoform:
         settings:
           projects:
             default:
    +          publicServer: '%env(MOSPARO_FORM_DEFAULT_PUBLIC_SERVER)%'
    +          verifyServer: '%env(MOSPARO_FORM_DEFAULT_VERIFY_SERVER)%'
    +          uuid: '%env(MOSPARO_FORM_DEFAULT_UUID)%'
    +          publicKey: '%env(MOSPARO_FORM_DEFAULT_PUBLIC_KEY)%'
    +          privateKey: '%env(MOSPARO_FORM_DEFAULT_PRIVATE_KEY)%'

..  _configure-site-set-settings:

Site set settings to configure the extension
-----------------------------------------------

..  typo3:site-set-settings:: PROJECT:/Configuration/Sets/Form/settings.definitions.yaml
    :name: form
    :Label: max=30
    :type:

.. _configure-via-typoscript:

Configure the mosparo Form via TypoScript (TypoScript File)
================================

You can add the settings over a TypoScript file like this:

..  code-block:: typoscript
    :caption: EXT:site_package/Configuration/TypoScript/constants.typoscript

    plugin.tx_mosparoform.settings {
        defaultProject=<Default mosparo project configuration that is used>

        projects {
            default {
                publicServer=<Host of your mosparo installation, which is used in the frontend>
                verifyServer=<Host of your mosparo installation, which is used at the backend verification>
                uuid=<Unique identification number of the project in mosparo>
                publicKey=<Public key of the project in mosparo>
                privateKey=<Private key of the project in mosparo>
            }
        }
    }

.. _configure-via-typoscript-constants-editor:

Configure the mosparo Form via TypoScript (Constants Editor)
================================

The settings for the `projects <https://documentation.mosparo.io/docs/usage/projects>`_ and validation themselves can be set via the :ref:`Submodule "Constant Editor" <t3tsref:constant-editor>`.

#. This can be opened under the :guilabel:`Sites > TypoScript` module using the :guilabel:`Constant Editor`.
#. You only have to select the entry “mosparo form” under :guilabel:`Selected category` in the editor.
