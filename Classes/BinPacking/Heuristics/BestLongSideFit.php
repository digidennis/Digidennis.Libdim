<?php
/**
 * Created by PhpStorm.
 * User: digid
 * Date: 02-03-2019
 * Time: 12:09
 */
namespace Digidennis\Libdim\BinPacking\Heuristics;

use Digidennis\Libdim\BinPacking\Box;
use Digidennis\Libdim\BinPacking\FreeSpaceBox;
use Digidennis\Libdim\BinPacking\Score;

class BestLongSideFit extends Base {

    public function calculateScore(FreeSpaceBox $freeSpaceBox, Box $box){
        if(!$box->isRotated() ){
            $leftOverHorizontal = abs($freeSpaceBox->getWidth() - $box->getWidth());
            $leftOverVertical = abs($freeSpaceBox->getHeight() - $box->getHeight());
        } else {
            $leftOverHorizontal = abs($freeSpaceBox->getWidth() - $box->getHeight());
            $leftOverVertical = abs($freeSpaceBox->getHeight() - $box->getWidth());
        }
        if($leftOverVertical>$leftOverHorizontal)
            return new Score($leftOverVertical, $leftOverHorizontal);
        return new Score($leftOverHorizontal,$leftOverVertical);
    }
}