<?php
/**
 * Created by PhpStorm.
 * User: digid
 * Date: 03-03-2019
 * Time: 10:36
 */

namespace Digidennis\Libdim\Objects;

use Digidennis\Libdim\BinPacking\ExpandingBin;
use Digidennis\Libdim\BinPacking\Box;;
use Digidennis\Libdim\Context\RectContext;
use Neos\Flow\Annotations as Flow;

/**
 * @Flow\Scope("session")
 */
class BinPackHelper {

    /**
     * @var array $dimensionalUnits
     */
    protected $dimensionalUnits = array();
    /**
     * @var array $boxes
     */
    protected $boxes = array();
    /**
     * @var ExpandingBin|null $bin
     */
    protected $bin;
    /**
     * @var int $width
     */
    protected $width = 0;

    /**
     * @param int $width
     * @param int $height
     * @param int $count
     * @param bool $canRotate
     * @return void
     * @Flow\Session(autoStart = TRUE)
     */
    public function addBox($width,$height,$count,$canRotate) {
        $newBox = new Box($width, $height);
        $newBox->setCanRotate($canRotate);
        for($i=0; $i<$count; $i++) {
            $this->boxes[] = clone($newBox);
        }
    }

    /**
     * @param DimensionalUnit $unit
     * @return void
     * @Flow\Session(autoStart = TRUE)
     */
    public function addDimensionalUnit(DimensionalUnit $unit) {
        $this->dimensionalUnits[] = $unit;
    }
    /**
     * @return array
     */
    public function getDimensionalunits() {
        return $this->dimensionalUnits;
    }
    /**
     * @return array
     */
    public function getBoxes() {
        return $this->boxes;
    }
    /**
     * @return ExpandingBin | null
     */
    public function getBin(){
        return $this->bin;
    }
    /**
     * @param int $idx
     * @return void
     * @Flow\Session(autoStart = TRUE)
     */
    public function removeBox($idx){
        unset($this->boxes[$idx]);
        $this->boxes = array_values($this->boxes);
    }

    /**
     * @param int $idx
     * @return void
     * @Flow\Session(autoStart = TRUE)
     */
    public function removeDimensionalUnit($idx){
        unset($this->dimensionalUnits[$idx]);
        $this->dimensionalUnits = array_values($this->dimensionalUnits);
    }

    public function packBoxes(int $width){

        $this->boxes = array();
        $rectExtractor = new RectContext();
        $this->bin = new ExpandingBin($width,10);
        $this->width = $width;
        /**
         * @var DimensionalUnit $dimensionalUnit
         */
        foreach ($this->dimensionalUnits as $dimensionalUnit) {
            $rects = $rectExtractor->extract(
                $dimensionalUnit->getStrategy(),
                $dimensionalUnit->getDimensions(),
                $dimensionalUnit->getConfig()
            );
            /**
             * @var Rect $rect
             */
            foreach ($rects as $rect) {
                $this->addBox($rect->getX(),$rect->getY(),1,$dimensionalUnit->isRotate());
            }
        }
        $compare = function ($a,$b){
            /**
             * @var Box $a
             * @var Box $b
             */
            if( $a->getHeight() == $b->getHeight() ) {
                return $a->area() <= $b->area();
            }
            return $a->getHeight() <= $b->getHeight();
        };
        usort($this->boxes, $compare );
        foreach ($this->boxes as $box) {
            $box->setPacked(false);
            $this->bin->insert( $box);
        }
    }

    public function reset() {
        $this->boxes = array();
        $this->dimensionalUnits = array();
        $this->bin = null;
    }

    /**
     * @return int
     */
    public function getWidth(){
        return $this->width;
    }
}