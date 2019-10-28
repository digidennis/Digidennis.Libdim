<?php
/**
 * Created by PhpStorm.
 * User: digid
 * Date: 01-03-2019
 * Time: 18:18
 */

namespace Digidennis\Libdim\BinPacking;


class Box extends FreeSpaceBox {
    /**
     * @var bool $packed, $canRotate, $isRotated
     */
    protected $packed, $canRotate, $rotated;

    /**
     * Box constructor.
     * @param int $width
     * @param int $height
     */
    public function __construct(int $width, int $height){
        parent::__construct($width, $height);
        $this->packed = false;
        $this->canRotate = false;
        $this->rotated = false;
    }
    /**
     * @return int
     */
    public function area(){
        return $this->width * $this->height;
    }
    /**
     * @return bool
     */
    public function isPacked(){
        return $this->packed;
    }

    /**
     * @return bool
     */
    public function isRotated() {
        return $this->rotated;
    }
    /**
     * @param bool $flag
     */
    public function setPacked($flag) {
        $this->packed = $flag;
    }

    /**
     * @param bool $flag
     */
    public function setRotated($flag) {
        $this->rotated = $flag;
    }
    /**
     * @return bool
     */
    public function canRotate(){
        return $this->canRotate;
    }
    /**
     * @return bool
     */
    public function getCanRotate(){
        return $this->canRotate;
    }
    /**
     * @return int
     */
    public function getWidth():int {
        if($this->isRotated())
            return $this->height;
        return $this->width;
    }
    /**
     * @return int
     */
    public function getHeight():int {
        if($this->isRotated())
            return $this->width;
        return $this->height;
    }
    /**
     * @param bool $flag
     */
    public function setCanRotate($flag) {
        $this->canRotate = $flag;
    }
    /**
     * @return string
     */
    public function getLabel() {
        return "$this->width x $this->height at [$this->x,$this->y]";
    }
}