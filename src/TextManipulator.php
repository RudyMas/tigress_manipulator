<?php

namespace Tigress;

/**
 * Class Text Manipulator (PHP version 8.4)
 *
 * @author Rudy Mas <rudy.mas@rudymas.be>
 * @copyright 2024, rudymas.be. (http://www.rudymas.be/)
 * @license https://opensource.org/licenses/GPL-3.0 GNU General Public License, version 3 (GPL-3.0)
 * @version 2024.11.28.0
 * @package Tigress\TextManipulator
 */
class TextManipulator
{
    /**
     * Get the version of the TextManipulator
     *
     * @return string
     */
    public static function version(): string
    {
        return '2024.11.28';
    }

    /**
     * @param int $numberOfCharacters The number of characters I want
     * @return string
     */
    public function randomText(int $numberOfCharacters): string
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ&#@$%£';
        $randomString = '';
        for ($x = 0; $x < $numberOfCharacters; $x++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $randomString;
    }

    /**
     * @param string $input Text that has to be processed
     * @return string
     */
    public function cleanHTML(string $input): string
    {
        return htmlentities($input, ENT_HTML5, 'UTF-8');
    }

    /**
     * @param string $input Text that has to be processed
     * @return string
     */
    public function uncleanHTML(string $input): string
    {
        return html_entity_decode($input, ENT_HTML5, 'UTF-8');
    }

    /**
     * @param string $input URL that has to be processed
     * @return string
     */
    public function cleanURL(string $input): string
    {
        $input = str_replace('/', '__', $input);
        return rawurlencode($input);
    }

    /**
     * @param string $input
     * @return string
     */
    public function cleanURLUTF8(string $input): string
    {
        $input = str_replace('/', '__', $input);
        return rawurlencode(mb_convert_encoding($input, 'UTF-8', 'ISO-8859-1'));
    }

    /**
     * @param string $input
     * @return string
     */
    public function uncleanURL(string $input): string
    {
        $input = str_replace('__', '/', $input);
        return rawurldecode($input);
    }

    /**
     * @param string $input
     * @return string
     */
    public function uncleanURLUTF8(string $input): string
    {
        $input = str_replace('__', '/', $input);
        return rawurldecode(mb_convert_encoding($input, 'ISO-8859-1', 'UTF-8'));
    }

    /**
     * @param string $input
     * @return string
     */
    public function rtrimStringNumber(string $input): string
    {
        return rtrim(rtrim($input, '0'), '.');
    }
}