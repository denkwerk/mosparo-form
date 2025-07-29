(function () {
  document.addEventListener("DOMContentLoaded", function () {
    // Get all mosparo div containers
    const mosparoContainers = document.querySelectorAll('div.mosparo-captcha');

    mosparoContainers.forEach(div => {
      if (div.dataset.initialized === "true") {
        return;
      }

      const options = {
        identifier: div.id,
        formId: div.dataset.formId,
        server: div.dataset.captchaServer,
        uuid: div.dataset.captchaUuid,
        publicKey: div.dataset.captchaPublickey
      };

      // Disable form submit button
      toggleSubmitButton(options.formId, false);

      // Init mosparo
      initializeMosparo(options);

      div.dataset.initialized = "true";
    });
  });

  /**
   * Initialization of mosparo
   *
   * @param options
   */
  function initializeMosparo(options) {
    let m;
    window.onload = function () {
      m = new mosparo(
        options.identifier,
        options.server,
        options.uuid,
        options.publicKey,
        {
          loadCssResource: true,
          onCheckForm: function (valid) {
            toggleSubmitButton(options.formId, valid);
          },
          onResetState: function () {
            toggleSubmitButton(options.formId, false);
          },
          onSwitchToInvisible: function () {
            toggleSubmitButton(options.formId, true);
          }
        }
      );
    };
  }

  /**
   * Returns the submit button element of the form
   *
   * @param formId string The ID of the form element
   * @returns {Element|null}
   */
  function getSubmitButton(formId) {
    const form = document.getElementById(formId);
    if (!form) {
      return null;
    }

    return form.querySelector('button[type="submit"], input[type="submit"]');
  }

  /**
   * Deactivates or activates the submit button of the form
   *
   * @param formId string The ID of the form element, if an empty string is provided, the toggle function will be disabled
   * @param enable boolean If true, the button is enabled and if false, it is disabled
   */
  function toggleSubmitButton(formId, enable) {
    if (formId) {
      const submitButton = getSubmitButton(formId);
      if (submitButton) {
        if (enable) {
          submitButton.removeAttribute("disabled");
        } else {
          submitButton.setAttribute("disabled", "true");
        }
      }
    }
  }
})();
