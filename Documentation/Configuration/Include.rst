:navigation-title: Include TypoScript

..  _include-into-your-project:

================================
Integrate mosparo Form into your TYPO3
================================

..  contents::
    :local:

..  _include-site-set:

Include static TypoScript via site set (TYPO3 v13 and above)
================================
.. attention::
    | mosparo Form depends on Fluid Styled Content.
    | You need to  :ref:`include Fluid Styled Content by site sets <typo3/cms-fluid-styled-content:include-site-set>` or :ref:` TypoScript <typo3/cms-fluid-styled-content:using-the-rendering-definitions>`

This extension comes with a site set called `denkwerk/mosparo-form`. To use it include
this set in your site configuration via

..  code-block:: diff
    :caption: config/sites/my-site/config.yaml (diff)

     base: 'https://example.com/'
     rootPageId: 1
    +dependencies:
    +  - denkwerk/mosparo-form
See also: :ref:`TYPO3 Explained, Using a site set as dependency in a site <t3coreapi:site-sets-usage>`.

Include site set via Site Manager
-----------------------------------------------
It is also possible to include mosparo Form as site dependency directly in the backend:

#. Got to module :guilabel:`Site Management > Sites`
#. Edit your site configuration.
#. In section :guilabel:`Sets for this Site` chose site set :guilabel:`denkwerk/mosparo-form`.

.. _include-typoscript:

Include static TypoScript via TypoScript module
================================
.. attention::
    | mosparo Form depends on Fluid Styled Content.
    | You need to  :ref:`include Fluid Styled Content by site sets <typo3/cms-fluid-styled-content:include-site-set>` or :ref:` TypoScript <typo3/cms-fluid-styled-content:using-the-rendering-definitions>`

This extension comes with static TypoScript that should be included if it has not been set in the :ref:`site set <_include-site-set>`.

#. Go to section :guilabel:`Includes > Include static (from extensions)`.

#. You should find the item "denkwerk - mosparo integration for EXT:form and Extbase-based forms" in the list
   :guilabel:`Available Items`. Click on this item to make it appear in the list
   :guilabel:`Selected Items`.

#. Now save your changes by clicking the :guilabel:`Save` button at the top.
..  seealso::
   For more details, see :ref:`t3tsref:static-includes`
