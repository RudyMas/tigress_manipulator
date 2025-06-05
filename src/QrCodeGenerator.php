<?php

namespace Tigress;

use chillerlan\QRCode\Common\EccLevel;
use chillerlan\QRCode\Output\QROutputInterface;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;

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

    public function __construct(array $setOptions = [])
    {
        $myOptions = [
            'version' => 7,
            'outputType' => QROutputInterface::GDIMAGE_PNG,
            'eccLevel' => EccLevel::L,
            'scale' => 10,
        ];

        $myOptions = array_merge($myOptions, $setOptions);

        $options = new QROptions($myOptions);
        $this->qr = new QRCode($options);
    }

    /**
     * @param string $data
     * @param string $filename
     * @return mixed
     */
    public function render(string $data, string $filename): mixed
    {
        return $this->qr->render($data, $filename);
    }
}