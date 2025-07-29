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

namespace Denkwerk\MosparoForm\Domain\Model\Dto;

/**
 * Class NormalizedData
 * @package Denkwerk\MosparoForm\Domain\Model\Dt
 */
class NormalizedData
{
    /**
     * @var array<string, mixed>
     */
    protected array $formData = [];

    /**
     * @var string[]
     */
    protected array $requiredFields = [];

    /**
     * @var string[]
     */
    protected array $verifiableFields = [];

    /**
     * @return array<string, mixed>
     */
    public function getFormData(): array
    {
        return $this->formData;
    }

    /**
     * @param array<string, mixed> $formData
     * @return void
     */
    public function setFormData(array $formData): void
    {
        $this->formData = $formData;
    }

    /**
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function addFormData(string $key, mixed $value): void
    {
        $this->formData[$key] = $value;
    }

    /**
     * @return string[]
     */
    public function getRequiredFields(): array
    {
        return $this->requiredFields;
    }

    /**
     * @param string[] $requiredFields
     * @return void
     */
    public function setRequiredFields(array $requiredFields): void
    {
        $this->requiredFields = $requiredFields;
    }

    /**
     * @param string $value
     * @return void
     */
    public function addRequiredField(string $value): void
    {
        $this->requiredFields[] = $value;
    }

    /**
     * @return string[]
     */
    public function getVerifiableFields(): array
    {
        return $this->verifiableFields;
    }

    /**
     * @param string[] $verifiableFields
     * @return void
     */
    public function setVerifiableFields(array $verifiableFields): void
    {
        $this->verifiableFields = $verifiableFields;
    }

    /**
     * @param string $value
     * @return void
     */
    public function addVerifiableField(string $value): void
    {
        $this->verifiableFields[] = $value;
    }
}
