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

namespace Denkwerk\MosparoForm\Event;

/**
 * This event lets extensions modify which form field types should be ignored or considered verifiable by the
 * FormNormalizer
 *
 * Class ModifyFormFieldTypeListsEvent
 * @package Denkwerk\MosparoForm\Event
 */
final class ModifyFormFieldTypeListsEvent
{
    /**
     * @param string[] $ignoredFieldTypes
     * @param string[] $verifiableFieldTypes
     */
    public function __construct(
        private array $ignoredFieldTypes,
        private array $verifiableFieldTypes
    ) {
    }

    /**
     * @return string[]
     */
    public function getIgnoredFieldTypes(): array
    {
        return $this->ignoredFieldTypes;
    }

    /**
     * @param string[] $types
     * @return void
     */
    public function setIgnoredFieldTypes(array $types): void
    {
        $this->ignoredFieldTypes = $types;
    }

    /**
     * @return string[]
     */
    public function getVerifiableFieldTypes(): array
    {
        return $this->verifiableFieldTypes;
    }

    /**
     * @param string[] $types
     * @return void
     */
    public function setVerifiableFieldTypes(array $types): void
    {
        $this->verifiableFieldTypes = $types;
    }
}
