<?php
declare(strict_types=1);

/*
 * This file is part of the "mosparo-form" Extension for TYPO3 CMS.
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace Denkwerk\MosparoForm\Hooks;

use TYPO3\CMS\Core\Http\ServerRequest;
use TYPO3\CMS\Form\Domain\Model\FormElements\Page;
use TYPO3\CMS\Form\Domain\Model\Renderable\RenderableInterface;
use TYPO3\CMS\Form\Domain\Runtime\FormRuntime;

/**
 * Class FormElementHooks
 * @package Denkwerk\MosparoForm\Hooks
 */
class FormElementHooks
{
    /**
     * This function will add the current FormDefinition to the "TYPO3_REQUEST", because we need the form definition in
     * the MosparoCaptchaValidator to verify the validated fields.
     *
     * @param FormRuntime $formRuntime
     * @param RenderableInterface $renderable
     * @param mixed $elementValue
     * @param array<int|string, mixed> $requestArguments
     * @return mixed
     */
    public function afterSubmit(FormRuntime $formRuntime, RenderableInterface $renderable, mixed $elementValue, array $requestArguments): mixed
    {
        // We only want to add the definition on the first call of the methode when $renderable should be of type "Page"
        if ($renderable instanceof Page &&
            isset($GLOBALS['TYPO3_REQUEST']) &&
            $GLOBALS['TYPO3_REQUEST'] instanceof ServerRequest &&
            $GLOBALS['TYPO3_REQUEST']->getAttribute('mosparoFormDefinition') === null
        ) {
            $GLOBALS['TYPO3_REQUEST'] = $GLOBALS['TYPO3_REQUEST']->withAttribute('mosparoFormDefinition', $formRuntime->getFormDefinition());
        }

        // We need to return the value
        return $elementValue;
    }
}
