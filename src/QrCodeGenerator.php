<?php

namespace Tigress;

use chillerlan\QRCode\Common\EccLevel;
use chillerlan\QRCode\Common\Version;
use chillerlan\QRCode\Output\QROutputInterface;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;

/**
 * Class QrCodeGenerator (PHP version 8.4)
 *
 * @author Rudy Mas <rudy.mas@rudymas.be>
 * @copyright 2025, rudymas.be. (http://www.rudymas.be/)
 * @license https://opensource.org/licenses/GPL-3.0 GNU General Public License, version 3 (GPL-3.0)
 * @version 2025.06.06.0
 * @package Tigress\QrCodeGenerator
 */
class QrCodeGenerator
{
    /**
     * @var QrCode
     */
    public QrCode $qr;

    public $options;

    /**
     * Get the version of QrCodeGenerator class.
     *
     * @return string
     */
    public static function version(): string
    {
        return '2025.06.06';
    }

    public function __construct(array $setOptions = [])
    {
        $myOptions = [
            'version' => Version::AUTO,
            'versionMin' => 7,
            'eccLevel' => EccLevel::L,
            'outputType' => QROutputInterface::GDIMAGE_PNG,
            'scale' => 10,
            'invertMatrix' => false,
            'drawLightModules' => true,
            'drawCircularModules' => false,
            'circleRadius' => 0.4,
            'addLogoSpace' => false,
            'logoSpaceWidth' => 15,
            'imageTransparent' => false,
            'quality' => 90,
            'gdImageUseUpscale' => false,
        ];

        $myOptions = array_merge($myOptions, $setOptions);

        $options = new QROptions($myOptions);
        $this->options = $options;
        $this->qr = new QRCode($options);
    }

    /**
     * @param array $options
     * @return self
     */
    public static function create(array $options = []): self
    {
        return new self($options);
    }

    /**
     * @param string $data
     * @param string|null $filename
     * @return mixed
     */
    public function render(string $data, ?string $filename = null): mixed
    {
        $this->qr->clearSegments();
        return $this->qr->render($data, $filename);
    }

    /**
     * @param string $data
     * @return string
     */
    public function base64(string $data): string
    {
        $this->qr->clearSegments();
        return 'data:image/png;base64,' . base64_encode($this->qr->render($data));
    }

    /**
     * @param string $data
     * @param string $logoPath
     * @param string|null $saveTo
     * @return string Base64 PNG image string
     */
    public function renderWithLogo(string $data, string $logoPath, ?string $saveTo = null): string
    {
        // 1. Render QR to PNG
        $this->qr->clearSegments();
        $png = $this->qr->render($data);

        // 2. Convert to GD image
        $qrImage = imagecreatefrompng($png);

        // 3. Load the logo
        $logo = imagecreatefrompng($logoPath);

        // 4. Get dimensions
        $qrW = imagesx($qrImage);
        $qrH = imagesy($qrImage);
        $logoW = imagesx($logo);
        $logoH = imagesy($logo);

        // 5. Resize logo
        $logoSize = (int)($qrW * 0.2); // logo = 20% of QR width
        $logoResized = imagecreatetruecolor($logoSize, $logoSize);
        imagealphablending($logoResized, false);
        imagesavealpha($logoResized, true);
        imagecopyresampled($logoResized, $logo, 0, 0, 0, 0, $logoSize, $logoSize, $logoW, $logoH);

        // 6. Center and merge
        $logoX = (int)(($qrW - $logoSize) / 2);
        $logoY = (int)(($qrH - $logoSize) / 2);
        imagecopy($qrImage, $logoResized, $logoX, $logoY, 0, 0, $logoSize, $logoSize);

        // 7. Output
        ob_start();
        imagepng($qrImage);
        imagedestroy($qrImage);

        $pngData = ob_get_clean();

        // Save to file if requested
        if ($saveTo !== null) {
            file_put_contents($saveTo, $pngData);
        }

        // 8. Return Base64-encoded PNG
        return 'data:image/png;base64,' . base64_encode($pngData);
    }
}