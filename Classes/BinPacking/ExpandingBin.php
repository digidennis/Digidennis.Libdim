<?php
/**
 * Created by PhpStorm.
 * User: digid
 * Date: 02-03-2019
 * Time: 17:44
 */

namespace Digidennis\Libdim\BinPacking;


class ExpandingBin extends Bin {

    /**
     * Bin constructor.
     * @param int $width
     * @param int $height
     * @param Base|null $heuristic
     */
    public function __construct( int $width, int $height, Base $heuristic = null ){
        parent::__construct($width, $height, $heuristic);
        $this->freeBoxes = [];
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

        if( !$box->isPacked()) {
            $newNode = null;
            $backedgePosition = $this->getHeight();
            foreach ($this->freeBoxes as $freeBox) {
                if ($freeBox->getY()+ $freeBox->getHeight() >= $backedgePosition ) {
                    if( $box->getWidth() <= $freeBox->getWidth() ) {
                        $newNode = &$freeBox;
                        if( $newNode->getHeight() < $box->getHeight() ) {
                            $newNode->setHeight($box->getHeight());
                        }
                        $clipedHeight = $newNode->getY() + $newNode->getHeight() - $backedgePosition;
                        if( $newNode->getX() > 0 ) {
                            $leftClipNode = new FreeSpaceBox($newNode->getX(), $clipedHeight );
                            $leftClipNode->setX(0);
                            $leftClipNode->setY($backedgePosition);
                            $this->freeBoxes[] = $leftClipNode;
                        }
                        if( $newNode->getX() < $this->getWidth() ) {
                            $rightClipNode = new FreeSpaceBox($this->getWidth()-$newNode->getX()-$newNode->getWidth(), $clipedHeight );
                            $rightClipNode->setX($newNode->getX()+$newNode->getWidth());
                            $rightClipNode->setY($backedgePosition);
                            $this->freeBoxes[] = $rightClipNode;
                        }
                        break;
                    }
                }
            }
            if( is_null($newNode) ){
                $newNode = new FreeSpaceBox($this->getWidth(), $box->getHeight());
                $newNode->setX(0);
                $newNode->setY($this->getHeight());
                $this->freeBoxes[] = $newNode;
            }
            $this->heuristic->findPositionForNewNode($box,$this->freeBoxes);
        }

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
     * @return int
     */
    public function getHeight(): int {
        $largestHeight = 0;
        /** @var Box $box */
        foreach ($this->getBoxes() as $box ){
            $height = $box->getY() + $box->getHeight();
            if ($height > $largestHeight )
                $largestHeight = $height;
        }
        return $largestHeight;
    }
}