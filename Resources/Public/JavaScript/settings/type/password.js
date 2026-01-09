import { BaseElement } from '@typo3/backend/settings/type/base.js';
import { html } from 'lit';
import { customElement, property } from 'lit/decorators.js';

const componentName = 'typo3-backend-settings-type-password';
const PASSWORD_PREFIX = '###ENCRYPTED###';
const MASKED_PASSWORD = '********';

@customElement(componentName)
class PasswordTypeElement extends BaseElement {
  @property({ type: String })
  value;

  constructor() {
    super(...arguments);
    this.originalEncryptedValue = null;
    this.hasUserInput = false;
    this.displayValue = '';
  }

  connectedCallback() {
    super.connectedCallback();
    if (this.value && typeof this.value === 'string' && this.value.startsWith(PASSWORD_PREFIX)) {
      this.originalEncryptedValue = this.value;
      this.displayValue = '********';
    } else if (this.value && this.value !== '') {
      this.displayValue = '********';
      this.originalEncryptedValue = this.value;
    }
  }

  render() {
    return html`
      <div class="input-grouped">
        <input
          type="password"
          id="${this.formid}"
          class="form-control"
          ?readonly="${this.readonly}"
          .value="${this.displayValue}"
          @input="${(e) => this.handleInput(e)}"
          @change="${(e) => this.handleChange(e)}"
          @focus="${() => this.handleFocus()}"
          @blur="${() => this.handleBlur()}"
        >
      </div>
    `;
  }

  handleFocus() {
    if (this.displayValue === '********') {
      this.displayValue = '';
      this.requestUpdate();
    }
  }

  handleBlur() {
    const input = this.shadowRoot?.querySelector('input');
    if (input && input.value !== '' && this.hasUserInput) {
      this.displayValue = '********';
      this.requestUpdate();
    } else if (input && input.value === '' && !this.hasUserInput && this.originalEncryptedValue) {
      this.value = this.originalEncryptedValue;
      this.displayValue = '********';
      this.requestUpdate();
    } else if (input && input.value === '' && this.hasUserInput && this.originalEncryptedValue) {
      this.value = this.originalEncryptedValue;
      this.displayValue = '********';
      this.hasUserInput = false;
      this.requestUpdate();
    }
  }

  handleInput(e) {
    this.hasUserInput = true;
    const value = e.target.value;
    this.displayValue = value;
    if (value !== '') {
      this.value = value;
    } else if (this.originalEncryptedValue) {
      this.value = this.originalEncryptedValue;
    } else {
      this.value = '';
    }
    this.requestUpdate();
  }

  handleChange(e) {
    const value = e.target.value;
    if (value === '' && !this.hasUserInput && this.originalEncryptedValue) {
      this.value = this.originalEncryptedValue;
      this.displayValue = '********';
    } else if (value === '' && this.hasUserInput && this.originalEncryptedValue) {
      this.value = this.originalEncryptedValue;
      this.displayValue = '********';
      this.hasUserInput = false;
    } else if (value === '') {
      this.value = '';
      this.displayValue = '';
    } else {
      this.value = value;
      this.displayValue = '********';
    }
    this.requestUpdate();
  }
}

export { componentName, PasswordTypeElement };

