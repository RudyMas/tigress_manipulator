<?php

namespace Tigress;

/**
 * Class Manipulator (PHP version 8.5)
 *
 * @author Rudy Mas <rudy.mas@rudymas.be>
 * @copyright 2024-2026, rudymas.be. (http://www.rudymas.be/)
 * @license Apache License 2.0 (http://www.apache.org/licenses/LICENSE-2.0)
 * @version 2026.05.24.1
 * @package Tigress\Manipulator
 */
class Manipulator
{
    /**
     * Get the version of the class.
     *
     * @return array
     */
    public static function version(): array
    {
        return [
            'Manipulator' => '2026.05.24',
            'BBCode' => BBCode::version(),
            'CalculateBirthday' => CalculateBirthday::version(),
            'CalculatePeriods' => CalculatePeriods::version(),
            'CalculatePregnancy' => CalculatePregnancy::version(),
            'DateManipulator' => DateManipulator::version(),
            'ImageManipulator' => ImageManipulator::version(),
            'NationalIdentification' => NationalIdentification::version(),
            'QrCodeGenerator' => QrCodeGenerator::version(),
            'TextManipulator' => TextManipulator::version(),
        ];
    }
}