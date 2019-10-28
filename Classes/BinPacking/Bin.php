<?php
/**
 * Created by PhpStorm.
 * User: digid
 * Date: 01-03-2019
 * Time: 12:49
 */

namespace Digidennis\Libdim\BinPacking;

use Digidennis\Libdim\BinPacking\Heuristics\Base;
use Digidennis\Libdim\BinPacking\Heuristics\BestAreaFit;
use Digidennis\Libdim\BinPacking\Heuristics\BestLongSideFit;
use Digidennis\Libdim\BinPacking\Heuristics\BestShortSideFit;
use Digidennis\Libdim\BinPacking\Heuristics\BottomLeft;

class Bin{
    /**
     * @var integer $width,$height
     */
    protected $width, $height;
    /**
     * @var array $boxes
     */
    protected $boxes;
    /**
     * @var Base $heuristic;
     */
    protected $heuristic;
    /**
     * @var array $freeBoxes
     */
    protected $freeBoxes;
    /**
     * Bin constructor.
     * @param int $width
     * @param int $height
     * @param Base|null $heuristic
     */
    public function __construct( int $width, int $height, Base $heuristic = null ){
        $this->width = $width;
        $this->height = $height;
        $this->boxes = [];
        $this->freeBoxes = [new FreeSpaceBox($this->width, $this->height)];
        $this->heuristic = $heuristic ? $heuristic : new BestShortSideFit();
    }
    /**
     * @return int
     */
    public function efficiency(){
        if(!count($this->boxes))
            return 100;
        $boxesArea = 0;
        /** @var Box $box */
        foreach ($this->boxes as $box){
            $boxesArea += $box->area();
        }
        return $boxesArea * 100 / $this->area();
    }

    /**
     * @return int
     */
    public function area(){
        return $this->getWidth() * $this->getHeight();
    }
    /**
     * @param Box $box
     * @return bool
     */
    public function insert(Box $box){
        if( $box->isPacked())
            return false;

        //find best x,y position for box
        $this->heuristic->findPositionForNewNode($box,$this->freeBoxes);
        if( !$box->isPacked())
            return false;
        $numBoxesToProcess = count($this->freeBoxes);
        $i = 0;
        while ($i < $numBoxesToProcess ){
            if( $this->splitFreeNode($this->freeBoxes[$i], $box)){
                unset($this->freeBoxes[$i]);
                $this->freeBoxes = array_values($this->freeBoxes);
                $numBoxesToProcess--;
            } else
                $i++;
        }
        $this->pruneFreeList();
        $this->boxes[] = $box;
        return true;
    }

    /**
     * @param FreeSpaceBox $freeNode
     * @param Box $usedNode
     * @return bool
     */
    protected function splitFreeNode(FreeSpaceBox $freeNode, Box $usedNode){
        /*
        if ($usedNode->getX() >= $freeNode->getX() + $freeNode->getWidth() ||
            $usedNode->getX() + $usedNode->getWidth() <= $freeNode->getX() ||
            $usedNode->getY() >= $freeNode->getY() + $freeNode->getHeight()||
            $usedNode->getY() + $usedNode->getHeight() <= $freeNode->getY() )
            return false;*/
        if( !$this->containedIn($usedNode, $freeNode))
            return false;

        $this->trySplitFreeNodeVertically($freeNode, $usedNode);
        $this->trySplitFreeNodeHorizontally($freeNode, $usedNode);
        return true;
    }

    /**
     * @param FreeSpaceBox $freeNode
     * @param Box $usedNode
     */
    protected function trySplitFreeNodeVertically(FreeSpaceBox $freeNode, Box $usedNode){
        if ($usedNode->getX() < $freeNode->getX() + $freeNode->getWidth() && $usedNode->getX() + $usedNode->getWidth() > $freeNode->getX() ){
            $this->tryLeaveFreeSpaceAtTop($freeNode,$usedNode);
            $this->tryLeaveFreeSpaceAtBottom($freeNode,$usedNode);
        }
    }

    /**
     * @param FreeSpaceBox $freeNode
     * @param Box $usedNode
     */
    protected function tryLeaveFreeSpaceAtTop(FreeSpaceBox $freeNode, Box $usedNode){
        if ($usedNode->getY() > $freeNode->getY() && $usedNode->getY() < $freeNode->getY() + $freeNode->getHeight() ){
            $newNode = clone $freeNode;
            $newNode->setHeight($usedNode->getY() - $newNode->getY() );
            $this->freeBoxes[] = $newNode;
        }
    }

    /**
     * @param FreeSpaceBox $freeNode
     * @param Box $usedNode
     */
    protected function tryLeaveFreeSpaceAtBottom(FreeSpaceBox $freeNode, Box $usedNode){
        if ($usedNode->getY() + $usedNode->getHeight() < $freeNode->getY() + $freeNode->getHeight()){
            $newNode = clone $freeNode;
            $newNode->setY($usedNode->getY() + $usedNode->getHeight() );
            $newNode->setHeight($freeNode->getY() + $freeNode->getHeight() - ($usedNode->getY() + $usedNode->getHeight()) );
            $this->freeBoxes[] = $newNode;
        }
    }

    /**
     * @param FreeSpaceBox $freeNode
     * @param Box $usedNode
     */
    protected function trySplitFreeNodeHorizontally(FreeSpaceBox $freeNode, Box $usedNode){
        if ($usedNode->getY() < $freeNode->getY() + $freeNode->getHeight() && $usedNode->getY() + $usedNode->getHeight() > $freeNode->getY() ){
            $this->tryLeaveFreeSpaceAtLeft($freeNode,$usedNode);
            $this->tryLeaveFreeSpaceAtRight($freeNode,$usedNode);
        }
    }
    /**
     * @param FreeSpaceBox $freeNode
     * @param Box $usedNode
     */
    protected function tryLeaveFreeSpaceAtLeft(FreeSpaceBox $freeNode, Box $usedNode){
        if ($usedNode->getX() > $freeNode->getX() && $usedNode->getX() < $freeNode->getX() + $freeNode->getWidth() ){
            $newNode = clone $freeNode;
            $newNode->setWidth($freeNode->getX() - $newNode->getX() );
            $this->freeBoxes[] = $newNode;
        }
    }
    /**
     * @param FreeSpaceBox $freeNode
     * @param Box $usedNode
     */
    protected function tryLeaveFreeSpaceAtRight(FreeSpaceBox $freeNode, Box $usedNode){
        if ($usedNode->getX() + $usedNode->getWidth() < $freeNode->getX() + $freeNode->getWidth()){
            $newNode = clone $freeNode;
            $newNode->setX($usedNode->getX() + $usedNode->getWidth() );
            $newNode->setWidth($freeNode->getX() + $freeNode->getWidth() - ($usedNode->getX() + $usedNode->getWidth()) );
            $this->freeBoxes[] = $newNode;
        }
    }

    /**
     * pruneFreeList
     */
    protected function pruneFreeList() {
        $i = 0;
        while($i < count($this->freeBoxes) ){
            $j = $i + 1;
            while($j < count($this->freeBoxes) ){
                if($this->containedIn($this->freeBoxes[$i], $this->freeBoxes[$j]) ){
                    unset($this->freeBoxes[$i]);
                    $this->freeBoxes = array_values($this->freeBoxes);
                    $i-- ;
                    break;
                }
                if($this->containedIn($this->freeBoxes[$j], $this->freeBoxes[$i]) ){
                    unset($this->freeBoxes[$j]);
                    $this->freeBoxes = array_values($this->freeBoxes);
                } else {
                    $j++ ;
                }
            }
            $i++ ;
        }
    }
    /**
     * Is boxA contained in boxB ?
     *
     * @param FreeSpaceBox $boxA
     * @param FreeSpaceBox $boxB
     * @return bool
     */
    protected function containedIn(FreeSpaceBox $boxA, FreeSpaceBox $boxB ): bool {
        return ($boxA->getX() >= $boxB->getX() && $boxA->getY() >= $boxB->getY() &&
            $boxA->getX()+$boxA->getWidth() <= $boxB->getX()+$boxB->getWidth() &&
            $boxA->getY()+$boxA->getHeight() <= $boxB->getY()+$boxB->getHeight() );
    }
    /**
     *  boxA overlap boxB ?
     *
     * @param FreeSpaceBox $boxA
     * @param FreeSpaceBox $boxB
     * @return bool
     */
    protected function overlap(FreeSpaceBox $boxA, FreeSpaceBox $boxB ): bool {
        return $boxA->getX() < $boxB->getX()+$boxB->getWidth() &&
            $boxA->getX()+$boxA->getWidth() > $boxB->getX() &&
            $boxA->getY()+$boxA->getHeight() > $boxB->getY() &&
            $boxA->getX() < $boxB->getY()+$boxB->getHeight();
    }

    /**
     * @return string
     */
    public function getLabel(): string {
        return $this->getWidth() . "x" . $this->getHeight() . " @ " . $this->efficiency() . "%";
    }

    /**
     * @return array
     */
    public function getBoxes(): array {
        return $this->boxes;
    }

    /**
     * @return array
     */
    public function getFreeBoxes(): array {
        return $this->freeBoxes;
    }

    /**
     * @return Base
     */
    public function getHeuristic(): Base {
        return $this->heuristic;
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
}
