<?php
namespace JuiceLib\Graphic;

use JuiceLib\Exception\Exception;

class Canvas implements GraphicResource
{
    const PNG = 0;
    const JPEG = 1;
    const GIF = 2;

    protected $resource;
    protected $width;
    protected $height;
    private $colors = array();

    function __construct($width, $height)
    {
        $this->width = (int)$width;
        $this->height = (int)$height;

        $this->resource = imagecreatetruecolor($this->width, $this->height);
    }


    /**
     * @param mixed $resource
     */
    public function setResource($resource)
    {
        $this->resource = $resource;
    }

    /**
     * @return mixed
     */
    public function getResource()
    {
        return $this->resource;
    }

    public function render($type = null)
    {
        switch ($type) {
            case self::GIF:
                imagegif($this->getResource());
                break;
            case self::JPEG:
                imagejpeg($this->getResource());
                break;
            default :
                imagepng($this->getResource());
        }
    }

    public function allocateColor($r = 0, $g = 0, $b = 0)
    {
        if (\is_a($r, "\\JuiceLib\\Color\\Color")) {
            /** @var \JuiceLib\Color\Color $r */
            $rgb = $r->toRGB();
            $r = $rgb->getR();
            $g = $rgb->getG();
            $b = $rgb->getB();
        } else {
            $r = (int)$r;
            $g = (int)$g;
            $b = (int)$b;
        }

        $index = sprintf("%s%s%s", $r, $g, $b);

        if (!isset($this->colors[$index])) {
            $this->colors[$index] = imagecolorallocate($this->getResource(), $r, $g, $b);
        }

        return $this->colors[$index];
    }

    public function fillBackground($color)
    {
        if (!\is_int($color)) {
            throw new \Exception("Invalid color resource.");
        }

        imagefill($this->getResource(), 0, 0, $color);
    }
} 