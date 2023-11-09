<?php
# ========================================================================#
#Класс обработки изображений
#Пример
#include("/admin/modules/general/resize_class.php");
#$resizeObj = new resize('/upload/images/test.jpg');
#$resizeObj -> resizeImage(150, 100, 0);
#$resizeObj -> saveImage('/upload/images/test_resize.jpg', 100);
# ========================================================================#

class resize
{
    // Переменные
    private $image;
    private $width;
    private $height;
    private $imageResized;

    public function __construct($fileName)
    {
        // Открываем файл
        $this->image = $this->openImage($_SERVER['DOCUMENT_ROOT'] . $fileName);

        // Получаем размер картинки
        $this->width  = imagesx($this->image);
        $this->height = imagesy($this->image);
    }

    ## --------------------------------------------------------

    private function openImage($file)
    {
        // Получаем расширение
        $extension = strtolower(strrchr($file, '.'));

        switch ($extension) {
            case '.jpg':
            case '.jpeg':
                $img = @imagecreatefromjpeg($file);
                break;
            case '.gif':
                $img = @imagecreatefromgif($file);
                break;
            case '.png':
                $img = @imagecreatefrompng($file);
                break;
            default:
                $img = false;
                break;
        }
        return $img;
    }

    ## --------------------------------------------------------

    public function resizeImage($newWidth, $newHeight, $option = "auto")
    {
        // Получаем оптимальные высоту и ширину заложеные по типу
        $optionArray = $this->getDimensions($newWidth, $newHeight, $option);

        $optimalWidth  = $optionArray['optimalWidth'];
        $optimalHeight = $optionArray['optimalHeight'];

        // Ресайзим картинку
        $this->imageResized = imagecreatetruecolor($optimalWidth, $optimalHeight);
        imagealphablending($this->imageResized, false);
        imagesavealpha($this->imageResized, true);
        imagecopyresampled($this->imageResized, $this->image, 0, 0, 0, 0, $optimalWidth, $optimalHeight, $this->width, $this->height);

        // Если нужно обрезать еще и обрезаем
        if ($option == 'crop') {
            $this->crop($optimalWidth, $optimalHeight, $newWidth, $newHeight);
        }
    }

    ## --------------------------------------------------------

    private function getDimensions($newWidth, $newHeight, $option)
    {

        switch ($option) {
            case 'exact':
                $optimalWidth  = $newWidth;
                $optimalHeight = $newHeight;
                break;
            case 'portrait':
                $optimalWidth  = $this->getSizeByFixedHeight($newHeight);
                $optimalHeight = $newHeight;
                break;
            case 'landscape':
                $optimalWidth  = $newWidth;
                $optimalHeight = $this->getSizeByFixedWidth($newWidth);
                break;
            case 'auto':
                $optionArray   = $this->getSizeByAuto($newWidth, $newHeight);
                $optimalWidth  = $optionArray['optimalWidth'];
                $optimalHeight = $optionArray['optimalHeight'];
                break;
            case 'crop':
                $optionArray   = $this->getOptimalCrop($newWidth, $newHeight);
                $optimalWidth  = $optionArray['optimalWidth'];
                $optimalHeight = $optionArray['optimalHeight'];
                break;
        }
        return array('optimalWidth' => $optimalWidth, 'optimalHeight' => $optimalHeight);
    }

    ## --------------------------------------------------------

    private function getSizeByFixedHeight($newHeight)
    {
        $ratio    = $this->width / $this->height;
        $newWidth = $newHeight * $ratio;
        return $newWidth;
    }

    private function getSizeByFixedWidth($newWidth)
    {

        $ratio     = $this->height / $this->width;
        $newHeight = $newWidth * $ratio;
        return $newHeight;
    }

    private function getSizeByAuto($newWidth, $newHeight)
    {
        if ($this->height < $this->width)
        // Ресайз по ширине
        {
            $optimalWidth  = $newWidth;
            $optimalHeight = $this->getSizeByFixedWidth($newWidth);
        } elseif ($this->height > $this->width)
        // Ресайз по высоте
        {
            $optimalWidth  = $this->getSizeByFixedHeight($newHeight);
            $optimalHeight = $newHeight;
        } else
        // Ресайз квадратный
        {
            if ($newHeight < $newWidth) {
                $optimalWidth  = $newWidth;
                $optimalHeight = $this->getSizeByFixedWidth($newWidth);
            } else if ($newHeight > $newWidth) {
                $optimalWidth  = $this->getSizeByFixedHeight($newHeight);
                $optimalHeight = $newHeight;
            } else {
                // Если одниковые то так и останутся
                $optimalWidth  = $newWidth;
                $optimalHeight = $newHeight;
            }
        }

        return array('optimalWidth' => $optimalWidth, 'optimalHeight' => $optimalHeight);
    }

    ## --------------------------------------------------------

    private function getOptimalCrop($newWidth, $newHeight)
    {

        $heightRatio = $this->height / $newHeight;
        $widthRatio  = $this->width / $newWidth;

        if ($heightRatio < $widthRatio) {
            $optimalRatio = $heightRatio;
        } else {
            $optimalRatio = $widthRatio;
        }

        $optimalHeight = $this->height / $optimalRatio;
        $optimalWidth  = $this->width / $optimalRatio;

        return array('optimalWidth' => $optimalWidth, 'optimalHeight' => $optimalHeight);
    }

    ## --------------------------------------------------------

    private function crop($optimalWidth, $optimalHeight, $newWidth, $newHeight)
    {
        // Находим центр для обрезки
        $cropStartX = ($optimalWidth / 2) - ($newWidth / 2);
        $cropStartY = ($optimalHeight / 2) - ($newHeight / 2);

        $crop = $this->imageResized;
        //imagedestroy($this->imageResized);

        // и обрезаем
        $this->imageResized = imagecreatetruecolor($newWidth, $newHeight);
        imagealphablending($this->imageResized, false);
        imagesavealpha($this->imageResized, true);
        imagecopyresampled($this->imageResized, $crop, 0, 0, $cropStartX, $cropStartY, $newWidth, $newHeight, $newWidth, $newHeight);
    }

    ## --------------------------------------------------------

    public function saveImage($savePath, $imageQuality = "100")
    {
        // Получаем расширение
        $extension = strrchr($savePath, '.');
        $extension = strtolower($extension);

        $savePath = $_SERVER['DOCUMENT_ROOT'] . $savePath;

        switch ($extension) {
            case '.jpg':
            case '.jpeg':
                if (imagetypes() & IMG_JPG) {
                    imagejpeg($this->imageResized, $savePath, $imageQuality);
                }
                break;

            case '.gif':
                if (imagetypes() & IMG_GIF) {
                    imagegif($this->imageResized, $savePath);
                }
                break;

            case '.png':

                $scaleQuality = round(($imageQuality / 100) * 9);

                $invertScaleQuality = 9 - $scaleQuality;

                if (imagetypes() & IMG_PNG) {
                    imagepng($this->imageResized, $savePath, $invertScaleQuality);
                }
                break;

            default:

                break;
        }

        imagedestroy($this->imageResized);
    }

    ## --------------------------------------------------------

    public function watermark($file, $watermark)
    {

        if (empty($file) | empty($watermark)) {
            return false;
        }

        // пути до файлов
        $wh = getimagesize($watermark);
        $fh = getimagesize($file);

        //Иногда может понадобиться наложить прозрачный png, тогда заменяем функцию на imagecreatefrompng
        $rwatermark = imagecreatefrompng($watermark);
        // Получаем расширение
        $extension = strtolower(strrchr($file, '.'));

        switch ($extension) {
            case '.jpg':
            case '.jpeg':
                $rfile = imagecreatefromjpeg($file);
                break;
            case '.gif':
                $rfile = imagecreatefromgif($file);
                break;
            case '.png':
                $rfile = imagecreatefrompng($file);
                break;
            default:
                $rfile = false;
                break;
        }

        //копируем watermark на картинку
        imagecopy($rfile, $rwatermark, $fh[0] / 2 - $wh[0] / 2, $fh[1] / 2 - $wh[1] / 2, 0, 0, $wh[0], $wh[1]);

        // Получаем расширение
        $extension = strrchr($file, '.');
        $extension = strtolower($extension);

        switch ($extension) {
            case '.jpg':
            case '.jpeg':
                if (imagetypes() & IMG_JPG) {
                    imagejpeg($rfile, $file, '100');
                }
                break;

            case '.gif':
                if (imagetypes() & IMG_GIF) {
                    imagegif($rfile, $file);
                }
                break;

            case '.png':
                $scaleQuality = round((100 / 100) * 9);

                $invertScaleQuality = 9 - $scaleQuality;

                if (imagetypes() & IMG_PNG) {
                    imagepng($rfile, $file, $invertScaleQuality);
                }
                break;

            default:

                break;
        }

        imagedestroy($rwatermark);
        imagedestroy($rfile);

        return true;
    }

}
