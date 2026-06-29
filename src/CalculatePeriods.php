<?php

namespace Tigress;

use DateMalformedStringException;
use DateTimeImmutable;

/**
 * Class CalculatePeriods (PHP version 8.5)
 * Calculate different beging- and end date of specific periods in time
 *
 * @author Rudy Mas <rudy.mas@rudymas.be>
 * @copyright 2024, rudymas.be. (http://www.rudymas.be/)
 * @license https://opensource.org/licenses/GPL-3.0 GNU General Public License, version 3 (GPL-3.0)
 * @version 2026.06.29.0
 * @package Tigress\CalculatePeriods
 */
class CalculatePeriods
{
    /**
     * Get the version of the class.
     *
     * @return string
     */
    public static function version(): string
    {
        return '2026.06.29';
    }

    /**
     * @throws DateMalformedStringException
     */
    public function getSchoolYear(string $date = 'today'): array
    {
        $today = new DateTimeImmutable($date);
        $year = (int) $today->format('Y');
        $month = (int) $today->format('n');

        // School year runs from 1 Sep to 31 Aug and must include today.
        if ($month >= 9) {
            $start = new DateTimeImmutable($year . '-09-01');
            $end = new DateTimeImmutable(($year + 1) . '-08-31');
        } else {
            $start = new DateTimeImmutable(($year - 1) . '-09-01');
            $end = new DateTimeImmutable($year . '-08-31');
        }

        return [$start, $end];
    }
}