:navigation-title: Add additional project

.. _add-additional-projects:

================================
Add additional project
================================
mosparo enables you to define multiple independent projects, each with its own settings, rules, and style that can be used across multiple websites or forms.
Before these projects can be selected by editors in TYPO3, they need to be configured.

..  contents::
    :local:

..  seealso::
    For more details on mosparo projects, see the official documentation:
    https://documentation.mosparo.io/docs/usage/projects


.. _add-additional-projects-to-site-set:

Add projects to site set settings (TYPO3 v13 and above)
================================
To configure custom projects in the site settings, perform the following steps.

#. Create an additional site set in your provider extension.

   ..  code-block:: yaml
       :caption: EXT:site_package/Configuration/Sets/ExtendMospraoForm/config.yaml

        name: exapmle/extendMosparo
        label: Extend mosparo project list

   ..  seealso::
       See :ref:`t3coreapi:site-sets-example-extension-multiple-sets`

#.  Now add a :ref:`settings.definitions.yaml <t3coreapi:site-settings-definition>` file to define additional projects in the :ref:`Site settings editor <t3coreapi:site-settings-editor>`.

    ..  code-block:: yaml
        :caption: EXT:site_package/Configuration/Sets/ExtendMospraoForm/settings.definitions.yaml

        categories:
            mosparoForm:
                label: 'Form mosparo'
            mosparoForm.projects:
                label: 'Projects'
                parent: mosparoForm
            mosparoForm.projects.EXAMPLE:
                label: 'EXAMPLE'
                parent: mosparoForm.projects

            settings:
                plugin.tx_mosparoform.settings.projects.EXAMPLE.publicServer:
                    type: string
                    default: ''
                    category: mosparoForm.projects.EXAMPLE
                    label: 'Public server'
                    description: 'Host of your mosparo installation, which is used in the frontend'
                plugin.tx_mosparoform.settings.projects.EXAMPLE.verifyServer:
                    type: string
                    default: ''
                    category: mosparoForm.projects.EXAMPLE
                    label: 'Verify Server'
                    description: 'Host of your mosparo installation, which is used at the backend verification'
                plugin.tx_mosparoform.settings.projects.EXAMPLE.uuid:
                    type: string
                    default: ''
                    category: mosparoForm.projects.EXAMPLE
                    label: 'UUID'
                    description: 'Unique identification number of the project in mosparo'
                plugin.tx_mosparoform.settings.projects.EXAMPLE.publicKey:
                    type: string
                    default: ''
                    category: mosparoForm.projects.EXAMPLE
                    label: 'Public key'
                    description: 'Public key of the project in mosparo'
                plugin.tx_mosparoform.settings.projects.EXAMPLE.privateKey:
                    type: string
                    default: ''
                    category: mosparoForm.projects.EXAMPLE
                    label: 'Private key'
                    description: 'Private key of the project in mosparo'

    ..  note::
        Replace "**EXAMPLE**" with a descriptive name that clearly indicates which project you're configuring. This name will be used as the identifier when selecting the project.

#. Now add the new set as “dependencies” to your base site set. The same way as for :ref:`_include-site-set`.

#. Clear the TYPO3 cache through the 'Maintenance' module :guilabel:`Admin Tools > Maintenance > Flush TYPO3 and PHP Cache > Flush cache` to apply the changes.
#. Go to :guilabel:`Site Management > Settings` in the TYPO3 backend, choose a site and enter the values for the newly created project configuration fields.
#. The additional project is now set up and can be referenced by its name using the "selectedProject"-option.
..  seealso::
    To add the new project to the dropdown in the Form Framework Editor, see: :ref:`_add-additional-projects-to-form-editor`


.. _add-additional-projects-via-typoscript:

Add projects via TypoScript
================================
To add an additional project, you only need to include the following in your :file:`setup.typoscript` file:

..  code-block:: typoscript
   :caption: EXT:site_package/Configuration/TypoScript/setup.typoscript

    plugin.tx_mosparoform.settings.projects.EXAMPLE {
        publicServer=
        verifyServer=
        uuid=
        publicKey=
        privateKey=
    }

..  note::
   Replace "**EXAMPLE**" with a descriptive name that clearly indicates which project you're configuring. This name will be used as the identifier when selecting the project.


.. _add-additional-projects-to-constants-editor:

Add projects to TypoScript Constants Editor
================================

You can also configure it to appear in the backend :ref:`Submodule "Constant Editor" <t3tsref:constant-editor>`. To do so, add the following configuration to your :file:`constants.typoscript` file:

..  code-block:: typoscript
    :caption: EXT:site_package/Configuration/TypoScript/constants.typoscript

     # customsubcategory=02_EXAMPLE=EXAMPLE project settings

     plugin.tx_mosparoform.settings.projects.EXAMPLE {
         # cat=Form mosparo/02_EXAMPLE/EXAMPLE/10; type=string; label=Host of your mosparo installation, which is used in the frontend
         publicServer=
         # cat=Form mosparo/02_EXAMPLE/EXAMPLE/20; type=string; label=Host of your mosparo installation, which is used at the backend verification
         verifyServer=
         # cat=Form mosparo/02_EXAMPLE/EXAMPLE/30; type=string; label=Unique identification number of the project in mosparo
         uuid=
         # cat=Form mosparo/02_EXAMPLE/EXAMPLE/40; type=string; label=Public key of the project in mosparo
         publicKey=
         # cat=Form mosparo/02_EXAMPLE/EXAMPLE/50; type=string; label=Private key of the project in mosparo
         privateKey=
     }

and then in the :file:`setup.typoscript`, we need to set the following:

..  code-block:: typoscript
    :caption: EXT:site_package/Configuration/TypoScript/setup.typoscript

     plugin.tx_mosparoform.settings.projects.EXAMPLE {
         publicServer={$plugin.tx_mosparoform.settings.projects.EXAMPLE.publicServer}
         verifyServer={$plugin.tx_mosparoform.settings.projects.EXAMPLE.verifyServer}
         uuid={$plugin.tx_mosparoform.settings.projects.EXAMPLE.uuid}
         publicKey={$plugin.tx_mosparoform.settings.projects.EXAMPLE.publicKey}
         privateKey={$plugin.tx_mosparoform.settings.projects.EXAMPLE.privateKey}
     }

..  note::
   Replace "**EXAMPLE**" with a descriptive name that clearly indicates which project you're configuring. This name will be used as the identifier when selecting the project.


.. _add-additional-projects-to-form-editor:

Add projects to the mosparo element in the Form Framework editor
================================
Editors can select from a list of projects in the mosparo form element's select box.
To expand this list, do the following:

#. Create a new Form Framework configuration file in your provider extension at :file:`Configuration/Yaml/ExtendMosparoCaptchaFormField.yaml`.

   ..  code-block:: yaml
       :caption: EXT:site_package/Configuration/Yaml/ExtendMosparoCaptchaFormField.yaml

        prototypes:
          standard:
            formElementsDefinition:
              MosparoCaptcha:
                formEditor:
                  editors:
                    300:
                      selectOptions:
                        20:
                          value: 'EXAMPLE'
                          label: 'EXAMPLE'
                        30:
                          value: 'EXAMPLE_2'
                          label: 'EXAMPLE_2'
                        ...

   ..  note::
       | Replace all "**EXAMPLE**" with a name/identifer of the project you previously added.
       | You can add multiple options just count the 20 up and suplicate the content.

#. Register your Form Framework configuration by adding the following to your  :file:`ext_localconf.php` file in your provider extension:

   ..  code-block:: php
       :caption: EXT:site_package/ext_localconf.php

        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTypoScriptSetup(
            trim(
                '
                    module.tx_form {
                        settings {
                            yamlConfigurations {
                                1916293528 = EXT:EXAMPLE/Configuration/Yaml/ExtendMosparoCaptchaFormField.yaml
                            }
                        }
                    }
                '
            )
        );

   ..  note::
       | Replace **"EXAMPLE"** with the name of your provider extension.
       | Also, make sure to change the number beforehand to avoid overriding any existing configuration.

#. Clear the TYPO3 cache through the 'Maintenance' module :guilabel:`Admin Tools > Maintenance > Flush TYPO3 and PHP Cache > Flush cache` to apply the changes.

