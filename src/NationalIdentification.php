<?php

namespace Tigress;

/**
 * Class NationalIdentification (PHP version 8.4)
 *
 * @author Rudy Mas <rudy.mas@rudymas.be>
 * @copyright 2025, rudymas.be. (http://www.rudymas.be/)
 * @license https://opensource.org/licenses/GPL-3.0 GNU General Public License, version 3 (GPL-3.0)
 * @version 2025.02.04.0
 * @package Tigress\Manipulator
 */
class NationalIdentification
{
    /**
     * Get the version of the class.
     *
     * @return string
     */
    public static function version(): string
    {
        return '2025.02.04';
    }

    /**
     * Get the birthdate from the national identification number by country
     *
     * @param string $country
     * @param mixed $unformattedNumber
     * @return string
     */
    public function getBirthdate(string $country, mixed $unformattedNumber): string
    {
        return match ($country) {
            'BE' => $this->getBirthdateBelgium($unformattedNumber),
            default => 'Not supported yet',
        };
    }

    /**
     * Format the national identification number by country
     *
     * Supported countries:
     * - Belgium (BE)
     * - Netherlands (NL)
     *
     * @param string $country
     * @param mixed $unformattedNumber
     * @return string
     */
    public function formatNumber(string $country, mixed $unformattedNumber): string
    {
        return match ($country) {
            'BE' => $this->formatBelgium($unformattedNumber),
            'NL' => $this->formatNetherlands($unformattedNumber),
            default => $unformattedNumber,
        };
    }

    /**
     * Format the Belgian national identification number
     *
     * @param mixed $unformattedNumber
     * @return false|string
     */
    private function formatBelgium(mixed $unformattedNumber): false|string
    {
        $unformattedNumber = preg_replace("/[^0-9]/", "", $unformattedNumber);
        if (mb_strlen($unformattedNumber) === 11) {
            $step1 = substr_replace($unformattedNumber, '.', 2, 0);
            $step2 = substr_replace($step1, '.', 5, 0);
            $step3 = substr_replace($step2, '-', 8, 0);
            return substr_replace($step3, '.', -2, 0);
        }
        return false;
    }

    /**
     * Format the Dutch national identification number
     *
     * @param mixed $unformattedNumber
     * @return false|string
     */
    private function formatNetherlands(mixed $unformattedNumber): false|string
    {
        // Implement the logic to format the Dutch national identification number
        $unformattedNumber = preg_replace("/[^0-9]/", "", $unformattedNumber);
        if (mb_strlen($unformattedNumber) === 9) {
            $step1 = substr_replace($unformattedNumber, '.', 4, 0);
            return substr_replace($step1, '.', -2, 0);
        }
        return false;
    }

    /**
     * Get the birthdate from the Belgian national identification number
     *
     * @param mixed $unformattedNumber
     * @return false|string
     */
    private function getBirthdateBelgium(mixed $unformattedNumber): false|string
    {
        $unformattedNumber = preg_replace("/[^0-9]/", "", $unformattedNumber);
        if (mb_strlen($unformattedNumber) === 11) {
            $year = substr($unformattedNumber, 0, 2);
            $month = substr($unformattedNumber, 2, 2);
            $day = substr($unformattedNumber, 4, 2);
            $year = ($year < 20) ? '20' . $year : '19' . $year;
            return $year . '-' . $month . '-' . $day;
        }
        return false;
    }
}