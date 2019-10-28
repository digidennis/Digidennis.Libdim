<?php
/**
 * Created by PhpStorm.
 * User: digid
 * Date: 01-03-2019
 * Time: 21:32
 */
namespace Digidennis\Libdim\BinPacking\Heuristics;

use Digidennis\Libdim\BinPacking\FreeSpaceBox;
use Digidennis\Libdim\BinPacking\Score;
use Digidennis\Libdim\BinPacking\Box;

class BestShortSideFit extends Base {

    public function calculateScore(FreeSpaceBox $freeSpaceBox, Box $box){
        if(!$box->isRotated() ){
            $leftOverHorizontal = abs($freeSpaceBox->getWidth() - $box->getWidth());
            $leftOverVertical = abs($freeSpaceBox->getHeight() - $box->getHeight());
        } else {
            $leftOverHorizontal = abs($freeSpaceBox->getWidth() - $box->getHeight());
            $leftOverVertical = abs($freeSpaceBox->getHeight() - $box->getWidth());
        }
        if($leftOverHorizontal>$leftOverVertical)
            return new Score($leftOverVertical, $leftOverHorizontal);
        return new Score($leftOverHorizontal,$leftOverVertical);
    }
}