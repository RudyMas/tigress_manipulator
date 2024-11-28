<?php

namespace Tigress;

/**
 * Class Date Manipulator (PHP version 8.4)
 *
 * @author Rudy Mas <rudy.mas@rudymas.be>
 * @copyright 2024, rudymas.be. (http://www.rudymas.be/)
 * @license https://opensource.org/licenses/GPL-3.0 GNU General Public License, version 3 (GPL-3.0)
 * @version 2024.11.28.0
 * @package Tigress\DateManipulator
 */
class DateManipulator
{
    /**
     * Get the version of the DateManipulator
     *
     * @return string
     */
    public static function version(): string
    {
        return '2024.11.28';
    }

    /**
     * Converts a correct formatted date to a Timestamp
     *
     * @param string $date
     * @return int
     */
    public function convertDateToTimestamp(string $date): int
    {
        return strtotime($date);
    }

    /**
     * Converts a Timestamp to a date (yyyy-mm-dd)
     *
     * @param int $timestamp
     * @return string
     */
    public function convertTimestampToDate(int $timestamp): string
    {
        return date('Y-m-d', $timestamp);
    }

    /**
     * Converts a Timestamp to a data (yyyy-mm-dd hh:mm:ss)
     *
     * @param int $timestamp
     * @return string
     */
    public function convertTimestampToDateTime(int $timestamp): string
    {
        return date('Y-m-d H:i:s', $timestamp);
    }
}