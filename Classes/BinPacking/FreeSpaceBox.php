<?php
/**
 * Created by PhpStorm.
 * User: digid
 * Date: 01-03-2019
 * Time: 18:13
 */

namespace Digidennis\Libdim\BinPacking;


class FreeSpaceBox {

    /**
     * @var integer $width, $height, $x,$y
     */
    protected $width, $height, $x, $y;

    /**
     * FreeSpaceBox constructor.
     * @param int $width
     * @param int $height
     */
    public function __construct( int $width, int $height){
        $this->width = $width;
        $this->height = $height;
        $this->x = $this->y = 0;
    }
    /**
     * @param FreeSpaceBox $other
     */
    public function assign(FreeSpaceBox $other){
        $this->x = $other->getX();
        $this->y = $other->getY();
        $this->width = $other->getWidth();
        $this->height = $other->getHeight();
    }
    /**
     * @return int
     */
    public function getX(): int {
        return $this->x;
    }
    /**
     * @return int
     */
    public function getY(): int {
        return $this->y;
    }
    /**
     * @param int $x
     */
    public function setX(int $x) {
        $this->x = $x;
    }
    /**
     * @param int $y
     */
    public function setY(int $y) {
        $this->y = $y;
    }
    /**
     * @return int
     */
    public function getWidth(): int {
        return $this->width;
    }
    /**
     * @return int
     */
    public function getHeight(): int {
        return $this->height;
    }
    /**
     * @param int $height
     */
    public function setHeight(int $height) {
        $this->height = $height;
    }
    /**
     * @param int $width
     */
    public function setWidth(int $width) {
        $this->width = $width;
    }
}