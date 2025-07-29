# TYPO3 Extension `mosparo_form`

This extension integrates the [mosparo](https://mosparo.io) spam protection system into TYPO3.

It enables spam protection for TYPO3 forms using the mosparo validation API and provides flexible mechanisms to support custom form setups.

It includes these features:

* Support for TYPO3 Form Framework
* Support for Extbase-based forms (`<f:form>`)
* Easy integration with custom form systems via a **Form Normalizer**

|                    | URL                                                            |
|--------------------|----------------------------------------------------------------|
| **Repository:**    | https://github.com/denkwerk/mosparo-form                       |
| **Documentation:** | https://docs.typo3.org/p/denkwerk/mosparo-form/main/en-us/     |
| **TER:**           | https://extensions.typo3.org/extension/mosparo_form            |

## Compatibility

| Version | TYPO3       | PHP       | mosparo | Supported Adapters                  |
|---------|-------------|-----------|---------|-------------------------------------|
| 1.0     | 12.4 – 13.4 | 8.1 – 8.3 | ≥ 1.3.7 | Form, Extbase, Custom Integration   |


## Custom Integration via Form Normalizer

You can support any form system (custom form systems like Powermail) by implementing your own `FormNormalizerInterface`.

Documentation:
→ [How to register a custom Form Normalizer](https://docs.typo3.org/p/denkwerk/mosparo-form/main/en-us/HowTos/FormNormalizer.html)

## FAQ

Common questions (e.g., multi-step forms, configuration tips) can be found in the [FAQ section](https://docs.typo3.org/p/denkwerk/mosparo-form/main/en-us/FAQ.html) of the documentation.

## Contributing

We welcome contributions! Feel free to open pull requests or issues on GitHub.
See the [Contribution Guide](https://docs.typo3.org/p/denkwerk/mosparo-form/CONTRIBUTING.md).

