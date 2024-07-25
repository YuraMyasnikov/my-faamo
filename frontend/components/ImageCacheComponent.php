<?php

namespace frontend\components;

use yii\base\Component as BaseComponent;

class ImageCacheComponent extends BaseComponent
{
    public function __construct(
        protected string $absolutePath,
        protected string $relativePath,
    ) {

    }

    public function resize(
        string $realAbsolutePath, 
        int|null $newWidth = null, 
        int|null $newHeight = null, 
        int|null $q = null
    ): string {

        $cachedName = $this->cachedName($realAbsolutePath, [
            'width' => $newWidth,
            'height' => $newHeight,
            'quality' => $q
        ]);
        if($this->isCached($cachedName)) {
            return $cachedName;
        }

        $info   = getimagesize($realAbsolutePath);
        $width  = $info[0];
        $height = $info[1];
        $type   = $info[2];

        switch ($type) {
            case 1:
                $img = imagecreatefromgif($realAbsolutePath);
                imagesavealpha($img, true);
                break;
            case 2:
                $img = imagecreatefromjpeg($realAbsolutePath);
                break;
            case 3:
                $img = imagecreatefrompng($realAbsolutePath);
                imagesavealpha($img, true);
                break;
            case 18: 
                $img = imagecreatefromwebp($realAbsolutePath);
                break;
        }

        if (empty($newWidth)) {
            $newWidth = ceil($newHeight / ($height / $width));
        }
        if (empty($newHeight)) {
            $newHeight = ceil($newWidth / ($width / $height));
        }

        $tmp = imagecreatetruecolor((int)$newWidth, (int)$newHeight);
        if ($type == 1 || $type == 3) {
            imagealphablending($tmp, true);
            imagesavealpha($tmp, true);
            $transparent = imagecolorallocatealpha($tmp, 0, 0, 0, 127);
            imagefill($tmp, 0, 0, $transparent);
            imagecolortransparent($tmp, $transparent);
        }

        $tw = ceil($newHeight / ($height / $width));
        $th = ceil($newWidth / ($width / $height));
        if ($tw < $newWidth) {
            imagecopyresampled($tmp, $img, ceil(($newWidth - $tw) / 2), 0, 0, 0, $tw, $newHeight, $width, $height);
        } else {
            imagecopyresampled($tmp, $img, 0, ceil(($newHeight - $th) / 2), 0, 0, $newWidth, $th, $width, $height);
        }

        switch ($type) {
            case 1: // gif
                break;
            case 2: // jpeg
                imagejpeg($tmp, $this->realPath($cachedName), $q ?? 75);
            case 3: // png
                break;
            case 18: // jpeg
                imagewebp($tmp, $this->realPath($cachedName), $q ?? 75);
        }

        return $cachedName;
    }

    public function cachedName(string $realPath, array $options = []): string
    {
        return md5($realPath . json_encode($options)) . '.jpg';
    }

    public function realPath(string|null $file = null): string
    {
        if(!$file) {
            return $this->absolutePath;
        }
        return $this->absolutePath . '/' . $file;
    }

    public function relativePath(string|null $file = null): string
    {
        if(!$file) {
            return $this->relativePath;
        }
        return $this->relativePath . '/' . $file;
    }

    public function isCached($filename): bool
    {
        return is_file($this->realPath($filename));
    }
}