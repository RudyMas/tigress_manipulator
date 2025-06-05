<?php

namespace Tigress;

use chillerlan\QRCode\QRCode;

/**
 * Class QrCodeGenerator (PHP version 8.4)
 *
 * @author Rudy Mas <rudy.mas@rudymas.be>
 * @copyright 2025, rudymas.be. (http://www.rudymas.be/)
 * @license https://opensource.org/licenses/GPL-3.0 GNU General Public License, version 3 (GPL-3.0)
 * @version 2025.06.05.0
 * @package Tigress\QrCodeGenerator
 */
class QrCodeGenerator
{
    /**
     * @var QrCode
     */
    public QrCode $qr;

    /**
     * Get the version of QrCodeGenerator class.
     *
     * @return string
     */
    public static function version(): string
    {
        return '2025.06.05';
    }

    public function __construct()
    {
        $this->qr = new QRCode();
    }

    /**
     * @param string $data
     * @param string $filename
     * @return mixed
     */
    public function simpleQrCode(string $data, string $filename): mixed
    {
        return $this->qr->render($data, $filename);
    }
}