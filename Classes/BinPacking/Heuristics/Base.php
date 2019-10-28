<?php
/**
 * Created by PhpStorm.
 * User: digid
 * Date: 01-03-2019
 * Time: 18:09
 */

namespace Digidennis\Libdim\BinPacking\Heuristics;

use Digidennis\Libdim\BinPacking\Box;
use Digidennis\Libdim\BinPacking\FreeSpaceBox;
use Digidennis\Libdim\BinPacking\Score;

abstract class Base {
    /**
     * @param Box $box
     * @param array $freeSpaceBoxes
     * @return Score
     */
    public function findPositionForNewNode( Box& $box,  array $freeSpaceBoxes ) {
        $topscorer = new Score();
        /**
         * @var FreeSpaceBox $freeSpaceBox
         */
        foreach ($freeSpaceBoxes as $freeSpaceBox ){
            $this->tryPlaceBoxIn($freeSpaceBox, $box,$topscorer);
        }
        return $topscorer;
    }
    /**
     * @param FreeSpaceBox $freeSpaceBox
     * @param Box $box
     * @param Score $topscorer
     */
    public function tryPlaceBoxIn(FreeSpaceBox $freeSpaceBox, Box& $box, Score& $topscorer ){
        if ($freeSpaceBox->getWidth() >= $box->getWidth() && $freeSpaceBox->getHeight() >= $box->getHeight() ){
            $score = $this->calculateScore($freeSpaceBox, $box);
            if($score->compare($topscorer) > 0 ){
                $box->setX($freeSpaceBox->getX() );
                $box->setY($freeSpaceBox->getY() );
                $box->setPacked(true);
                $topscorer->assign($score);
            }
        }
        if ($box->canRotate() ){
            $box->setRotated(true);
            if($freeSpaceBox->getWidth() >= $box->getWidth() && $freeSpaceBox->getHeight() >= $box->getHeight() ){
                $score = $this->calculateScore($freeSpaceBox, $box);
                if($score->compare($topscorer) > 0 ){
                    $box->setX($freeSpaceBox->getX() );
                    $box->setY($freeSpaceBox->getY() );
                    $box->setPacked(true);
                    $topscorer->assign($score);
                } else {
                    $box->isRotated(false);
                }
            }
        }
    }
    /**
     * @param FreeSpaceBox $freeSpaceBox
     * @param int $width
     * @param int $height
     * @return Score $score
     */
    abstract function calculateScore(FreeSpaceBox $freeSpaceBox, Box $box );

}