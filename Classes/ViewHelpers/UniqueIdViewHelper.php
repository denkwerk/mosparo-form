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

namespace Denkwerk\MosparoForm\ViewHelpers;

use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * Generates a unique ID for mosparo captchas on pages with multiple forms.
 *
 * Example:
 * <div id="mosparo-{mosparoform:uniqueId()}" ...></div>
 *
 * Class UniqueIdViewHelper
 * @package Denkwerk\MosparoForm\ViewHelpers
 */
class UniqueIdViewHelper extends AbstractViewHelper
{
    /**
     * @return string
     * @throws \Random\RandomException
     */
    public function render(): string
    {
        return bin2hex(random_bytes(4));
    }
}
