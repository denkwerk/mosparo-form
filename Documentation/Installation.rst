:navigation-title: Installation


.. _installation:

============
Installation
============

..  contents::
    :local:

..  _installation-setup:

Installation and configuration
=====================

.. rst-class:: bignums

#. :ref:`_installation-composer` or :ref:`_installation-classic`.

#. :ref:`_include-site-set` or :ref:`_include-typoscript`.

#. :ref:`_configure-via-site-set` or :ref:`_configure-via-typoscript`

#. (Optional) :ref:`_add-additional-projects`

#. Start adding mosparo to your forms!

..  _installation-composer:

Install mosparo form with Composer
=======================================

Install the extension via Composer:

..  code-block:: bash

    composer req denkwerk/mosparo-form

See also `Installing extensions, TYPO3 Getting started <https://docs.typo3.org/permalink/t3start:installing-extensions>`_.

..  _installation-classic:

Install mosparo form in Classic Mode
=========================================

| Or download the extension from `https://extensions.typo3.org/package/mosparo_form <https://extensions.typo3.org/package/mosparo_form>`_ and install it in the Extension Manager.
| The extension depends on the PHP package `mosparo/php-api-client <https://github.com/mosparo/php-api-client>`_.
| Make sure this package is installed in your TYPO3 environment or otherwise available so the extension can function properly.
