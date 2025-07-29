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

use Denkwerk\MosparoForm\Domain\Model\Dto\NormalizedData;
use Denkwerk\MosparoForm\Domain\Model\Form\MosparoFormDefinitionInterface;

/**
 * Class ExtbaseNormalizer
 * @package Denkwerk\MosparoForm\FormNormalizer
 */
class ExtbaseNormalizer implements FormNormalizerInterface
{
    /**
     * @param MosparoFormDefinitionInterface|null $formDefinition
     * @return bool
     */
    public function supports(?MosparoFormDefinitionInterface $formDefinition): bool
    {
        return $formDefinition === null;
    }

    /**
     * Converts $_POST data into a structured object for mosparo backend verification.
     *
     * Used for all requests that do NOT come from the TYPO3 Form Framework or other data normalizers.
     *
     * @param array<int|string, mixed> $postData The $_POST variable value
     * @param MosparoFormDefinitionInterface|null $formDefinition
     * @return NormalizedData
     */
    public function normalize(array $postData, ?MosparoFormDefinitionInterface $formDefinition): NormalizedData
    {
        $data = new NormalizedData();

        $data->setFormData($this->flattenArray($postData));

        return $data;
    }

    /**
     * mosparo expects the exact field keys as name so "tx_extbaseform_exampleform[contact][topic]" so we need
     * to convert the multidimensional array to a simple array with corresponding array keys
     * Example:
     * [
     *   'test' => [
     *      'a' => 'bla',
     *      'c'=> 'blub'
     *   ],
     *   'fdgfg' => 'fsdfsd',
     *   'tesfd' => [
     *      'dfsd' => ['dd' => 'ddd']
     *   ]
     * ]
     * will be converted to
     * [
     *   'test[a]' => 'bla',
     *   'test[c]' => 'blub',
     *   'fdgfg' => 'fsdfsd',
     *   'tesfd[dfsd][dd]' => 'ddd'
     * ]
     *
     * @param array<int|string, mixed> $array
     * @param string $prefix
     * @return array<string, mixed>
     */
    protected function flattenArray(array $array, string $prefix = ''): array
    {
        $result = [];
        if (count($array) > 0) {
            foreach ($array as $key => $value) {
                $newKey = $prefix . (empty($prefix) ? '' : '[') . $key . (empty($prefix) ? '' : ']');
                if (is_array($value)) {
                    // NumericArrays are usally fields with the option "multiple" and then we need to set this as array otherwise the vaildation will fail.
                    // mosparo expect fields of type multiple like "tx_extbaseform_exampleform[form][subForm][multiSelect] = [0 => 'item1', 1 => 'item2']" not "tx_extbaseform_exampleform[form][subForm][multiSelect][0] = "item1""
                    if ($this->isNumericArray($value)) {
                        $result[$newKey] = $value;
                    } else {
                        $result = array_merge($result, $this->flattenArray($value, $newKey));
                    }
                } else {
                    $result[$newKey] = $value;
                }
            }
        }
        return $result;
    }

    /**
     * Check if this array is a continuous numeric array
     *
     * @param array<int|string, mixed> $array
     * @return bool
     */
    protected function isNumericArray(array $array): bool
    {
        return array_keys($array) === range(0, count($array) - 1);
    }
}
