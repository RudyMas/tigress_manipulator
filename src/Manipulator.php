<?php

namespace Tigress;

/**
 * Class Text Manipulator (PHP version 8.4)
 *
 * @author Rudy Mas <rudy.mas@rudymas.be>
 * @copyright 2024, rudymas.be. (http://www.rudymas.be/)
 * @license https://opensource.org/licenses/GPL-3.0 GNU General Public License, version 3 (GPL-3.0)
 * @version 2024.11.28.0
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
            'Manipulator' => '2024.11.28',
            'BBCode' => BBCode::version(),
            'CalculatePregnancy' => CalculatePregnancy::version(),
            'DateManipulator' => DateManipulator::version(),
            'TextManipulator' => TextManipulator::version(),
        ];
    }
}