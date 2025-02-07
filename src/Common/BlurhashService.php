<?php 

namespace App\Common;

use kornrunner\Blurhash\Blurhash;

final class BlurhashService
{
    public function hashFromFile(string $imagePath, int $componentX = 5, int $componentY = 4): string
    {
        return $this->hashFromFileContent(file_get_contents($imagePath), $componentX, $componentY);
    }

    public function hashFromFileContent(string $content, int $componentX = 5, int $componentY = 4): string
    {
        ini_set('memory_limit', '-1');
        
        $image = imagecreatefromstring($content);
        $width = imagesx($image);
        $height = imagesy($image);

        $pixels = [];
        for ($y = 0; $y < $height; ++$y) {
            $row = [];
            for ($x = 0; $x < $width; ++$x) {
                $index = imagecolorat($image, $x, $y);
                $colors = imagecolorsforindex($image, $index);

                $row[] = [$colors['red'], $colors['green'], $colors['blue']];
            }
            $pixels[] = $row;
        }

        return Blurhash::encode($pixels, $componentX, $componentY);
    }

    public function hashToImage(string $hash, int $width, int $height): string
    {
        $pixels = Blurhash::decode($hash, $width, $height);
        $decodedImage  = imagecreatetruecolor($width, $height);
        for ($y = 0; $y < $height; ++$y) {
            for ($x = 0; $x < $width; ++$x) {
                [$r, $g, $b] = $pixels[$y][$x];
                imagesetpixel($decodedImage, $x, $y, imagecolorallocate($decodedImage, $r, $g, $b));
            }
        }

        ob_start();
        imagepng($decodedImage);
        $decodedImageData = ob_get_contents();

        return ob_get_clean();
    }
}
