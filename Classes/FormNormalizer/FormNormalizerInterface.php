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

namespace Denkwerk\MosparoForm\FormNormalizer;

use Denkwerk\MosparoForm\Domain\Model\Form\MosparoFormDefinitionInterface;
use Denkwerk\MosparoForm\Domain\Model\Dto\NormalizedData;

interface FormNormalizerInterface
{
    /**
     * @param MosparoFormDefinitionInterface|null $formDefinition
     * @return bool
     */
    public function supports(?MosparoFormDefinitionInterface $formDefinition): bool;

    /**
     * @param array<int|string, mixed> $postData The $_POST variable value
     * @param MosparoFormDefinitionInterface $formDefinition
     * @return NormalizedData
     */
    public function normalize(array $postData, MosparoFormDefinitionInterface $formDefinition): NormalizedData;
}
