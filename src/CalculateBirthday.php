<?php

namespace Tigress;

use DateMalformedStringException;
use DateTime;
use InvalidArgumentException;

/**
 * Class Calculate Birthday (PHP version 8.4)
 * Calculate the periode a person has been alive
 *
 * @author Rudy Mas <rudy.mas@rudymas.be>
 * @copyright 2024, rudymas.be. (http://www.rudymas.be/)
 * @license https://opensource.org/licenses/GPL-3.0 GNU General Public License, version 3 (GPL-3.0)
 * @version 2024.12.18.0
 * @package Tigress\CalculateBirthday
 */
class CalculateBirthday
{
    private string $date = '1970-01-01';
    private string $time = '00:00:00';

    /**
     * Get the version of the class.
     *
     * @return string
     */
    public static function version(): string
    {
        return '2024.12.18';
    }

    /**
     * Calculate the periode a person has been alive
     *
     * @param string $type
     * @return float|false|array|int
     * @throws DateMalformedStringException
     */
    public function calculate(string $type): float|false|array|int
    {
        $startDateTime = new DateTime($this->date . ' ' . $this->time);
        $currentDateTime = new DateTime();
        $interval = $startDateTime->diff($currentDateTime);

        return match ($type) {
            'years' => $interval->y,
            'months' => ($interval->y * 12) + $interval->m,
            'weeks' => floor(($interval->days) / 7),
            'days' => $interval->days,
            'hours' => $interval->days * 24 + $interval->h,
            'minutes' => ($interval->days * 24 * 60) + ($interval->h * 60) + $interval->i,
            'seconds' => ($interval->days * 24 * 60 * 60) + ($interval->h * 60 * 60) + ($interval->i * 60) + $interval->s,
            'full' => [
                'years' => $interval->y,
                'months' => $interval->m,
                'days' => $interval->d,
                'hours' => $interval->h,
                'minutes' => $interval->i,
                'seconds' => $interval->s
            ],
            default => throw new InvalidArgumentException("Invalid type: $type. Allowed types are years, months, weeks, days, hours, minutes, seconds, full."),
        };
    }

    /**
     * Set the date
     *
     * @param string $date
     * @return void
     */
    public function setDate(string $date): void
    {
        $this->date = $date;
    }

    /**
     * Get the date
     *
     * @return string
     */
    public function getDate(): string
    {
        return $this->date;
    }

    /**
     * Set the time
     *
     * @param $time
     * @return void
     */
    public function setTime($time): void
    {
        $this->time = $time;
    }

    /**
     * Get the time
     *
     * @return string
     */
    public function getTime(): string
    {
        return $this->time;
    }
}