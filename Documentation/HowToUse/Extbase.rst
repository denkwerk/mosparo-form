:navigation-title: How to use (Extbase forms)

..  _how-to-use-form-extbase:

================================
How to use (Extbase forms <f:form>)
================================

This section explains how to integrate the mosparo form element into an :ref:`Extbase form <f:form> <t3viewhelper:typo3-fluid-form>`.

#.  Add the validator to your controller

    ..  code-block:: php
        :caption: EXT:my_extension/Classes/Controller/SearchController.php

        use TYPO3\CMS\Extbase\Annotation\Validate;
        use Denkwerk\MosparoForm\Validation\Validator\MosparoCaptchaValidator;

        ...

        #[Validate(['param' => 'form', 'validator' => MosparoCaptchaValidator::class, 'options' => [
            'selectedProject' => 'example',
            'requiredFields' => 'tx_exampleform_exampleform[form][name],tx_exampleform_exampleform[form][email],'
        ]])]
        public function searchAction(array $search = []): ResponseInterface

    * You can specify the mosparo project to be used by setting the :php:`selectedProject` option.
    * To ensure that all relevant form fields are checked by mosparo, use the :php:`requiredFields` and optionally :php:`verifiableFields` options.
    ..  seealso::
        | See the mosparo documentation:
        | `Ignored fields <https://documentation.mosparo.io/docs/integration/ignored_fields>`__
        | `Bypass protection <https://documentation.mosparo.io/docs/integration/bypass_protection>`__

#. | Include the mosparo Extbase partials in your TypoScript configuration
   | We include the partials because mosparo provides a custom Fluid partial ::file:`/Resources/Private/Frontend/Partials/ExtbaseForms/MosparoCaptcha.html` that renders the required HTML container, loads the JavaScript, and applies the selected configuration automatically.

   ..  code-block:: typoscript
       :caption: EXT:my_extension/Configuration/TypoScript/setup.typoscript

       plugin.tx_blogexample {
          view {
            templateRootPaths.10 = {$plugin.tx_blogexample.view.templateRootPath}
            partialRootPaths.10 = {$plugin.tx_blogexample.view.partialRootPath}
            partialRootPaths.20 = EXT:mosparo_form/Resources/Private/Frontend/Partials/ExtbaseForms/
            layoutRootPaths.10 = {$plugin.tx_blogexample.view.layoutRootPath}
          }
       }

   ..  note::
       For more information, see the TYPO3 documentation: :ref:`t3coreapi:extbase-view-configuration`

#. | Render the mosparo partial in your form template
   | **Minimal**:

   ..  code-block:: html
       :caption: EXT:my_extension/Resources/Private/Templates/Form/Form.html

       <f:render partial="MosparoCaptcha" />

   **Full configuration**:

   ..  code-block:: html
       :caption: EXT:my_extension/Resources/Private/Templates/Form/Form.html

       <f:render partial="MosparoCaptcha" arguments="{
           selectedProject: 'default',
           formId: 'form'
       }" />

   * | Use the :php:`selectedProject` argument to choose a specific mosparo project.
     | If omitted, the default project will be used.
   * | To enable dynamic enabling/disabling of the submit button based on mosparo validation, pass the form’s HTML id as the :php:`formId` argument.
     | The JavaScript included by the partial will then control the first submit button found in the form.
     | If formId is not set or empty, this functionality will be disabled.
   * When using Invisible Mode, the submit button will automatically be enabled after mosparo is initialized.

#. | Display validation errors
   | If validation fails and you don't render the error output, users won't see any feedback.

   ..  code-block:: html
       :caption: EXT:my_extension/Resources/Private/Templates/Form/Form.html

        <f:form.validationResults for="form">
            <f:if condition="{validationResults.errors}">
                <ul>
                    <f:for each="{validationResults.errors}" as="error">
                        <li>{error.message}</li>
                    </f:for>
                </ul>
            </f:if>
        </f:form.validationResults>

   ..  note::
       For more information, see the TYPO3 documentation: :ref:`t3viewhelper:typo3-fluid-form-validationresults`
