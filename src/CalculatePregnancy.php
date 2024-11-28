<?php

namespace Tigress;

/**
 * Class Calculate Pregnancy (PHP version 8.4)
 * Calculate weeks/days that someone is pregnant
 *
 * @author Rudy Mas <rudy.mas@rudymas.be>
 * @copyright 2024, rudymas.be. (http://www.rudymas.be/)
 * @license https://opensource.org/licenses/GPL-3.0 GNU General Public License, version 3 (GPL-3.0)
 * @version 2024.11.28.0
 * @package Tigress\CalculatePregnancy
 */
class CalculatePregnancy
{
    private $DM;
    private $pregnancyDuration;
    private $periodWeek;
    private $periodDay;

    /**
     * Get the version of the CalculatePregnancy
     *
     * @return string
     */
    public static function version(): string
    {
        return '2024.11.28';
    }

    /**
     * CalculatePregnancy constructor.
     */
    public function __construct()
    {
        $this->DM = new DateManipulator();
        $this->pregnancyDuration = 281 * 24 * 60 * 60;
        $this->periodDay = 24 * 60 * 60;
        $this->periodWeek = 7 * $this->periodDay;
    }

    /**
     * Calculate the weeks of pregnancy
     *
     * @param string $deliveryDate
     * @param string $referenceDate
     * @return int
     */
    public function calculateWeeks(string $deliveryDate, string $referenceDate): int
    {
        $timestampDeliveryDate = $this->DM->convertDateToTimestamp($deliveryDate);
        $timestampReferenceDate = $this->DM->convertDateToTimestamp($referenceDate);
        $timestampConceptionDate = $timestampDeliveryDate - $this->pregnancyDuration;
        return ceil(($timestampReferenceDate - $timestampConceptionDate) / $this->periodWeek);
    }

    /**
     * Calculate the days of pregnancy
     *
     * @param string $deliveryDate
     * @param string $referenceDate
     * @return int
     */
    public function calculateDays(string $deliveryDate, string $referenceDate): int
    {
        $timestampDeliveryDate = $this->DM->convertDateToTimestamp($deliveryDate);
        $timestampReferenceDate = $this->DM->convertDateToTimestamp($referenceDate);
        $timestampConceptionDate = $timestampDeliveryDate - $this->pregnancyDuration;
        return ceil(($timestampReferenceDate - $timestampConceptionDate) / $this->periodDay);
    }

    /**
     * Calculate the weeks of pregnancy by Timestamp
     *
     * @param int $timestampDeliveryDate
     * @param int $timestampReferenceDate
     * @return int
     */
    public function calculateWeeksByTimestamp(int $timestampDeliveryDate, int $timestampReferenceDate): int
    {
        $timestampConceptionDate = $timestampDeliveryDate - $this->pregnancyDuration;
        return ceil(($timestampReferenceDate - $timestampConceptionDate) / $this->periodWeek);
    }

    /**
     * Calculate the days of pregnancy by Timestamp
     *
     * @param int $timestampDeliveryDate
     * @param int $timestampReferenceDate
     * @return int
     */
    public function calculateDaysByTimestamp(int $timestampDeliveryDate, int $timestampReferenceDate): int
    {
        $timestampConceptionDate = $timestampDeliveryDate - $this->pregnancyDuration;
        return ceil(($timestampReferenceDate - $timestampConceptionDate) / $this->periodDay);
    }
}