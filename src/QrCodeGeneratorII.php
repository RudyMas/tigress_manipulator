<?php

namespace Tigress;

use BaconQrCode\Renderer\Image\ImagickImageBackEnd;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer as WriterAlias;

/**
 * Class QrCodeGenerator (PHP version 8.4)
 *
 * @author Rudy Mas <rudy.mas@rudymas.be>
 * @copyright 2025, rudymas.be. (http://www.rudymas.be/)
 * @license https://opensource.org/licenses/GPL-3.0 GNU General Public License, version 3 (GPL-3.0)
 * @version 2025.06.05.0
 * @package Tigress\QrCodeGenerator
 */
class QrCodeGeneratorII
{
    /**
     * Get the version of QrCodeGenerator class.
     *
     * @return string
     */
    public static function version(): string
    {
        return '2025.06.05';
    }

    /**
     * @param string $data
     * @return string
     */
    public function render(string $data): string
    {
        $renderer = new ImageRenderer(
            new RendererStyle(400),
            new ImagickImageBackEnd()
        );

        $writer = new WriterAlias($renderer);
        return $writer->writeString($data);
    }
}