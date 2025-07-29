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

namespace Denkwerk\MosparoForm\Domain\Model\Form;

use TYPO3\CMS\Form\Domain\Model\FormDefinition;

/**
 * Class FormsMosparoFormDefinition
 * @package Denkwerk\MosparoForm\Domain\Model\Form
 */
class FormsMosparoFormDefinition implements MosparoFormDefinitionInterface
{
    public function __construct(protected FormDefinition $formFrameworkDefinition)
    {
    }

    public function getFormFrameworkDefinition(): FormDefinition
    {
        return $this->formFrameworkDefinition;
    }

    public function setFormFrameworkDefinition(FormDefinition $formFrameworkDefinition): void
    {
        $this->formFrameworkDefinition = $formFrameworkDefinition;
    }
}
