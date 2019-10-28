<?php
/**
 * Created by PhpStorm.
 * User: digid
 * Date: 02-03-2019
 * Time: 12:17
 */

namespace Digidennis\Libdim\BinPacking\Heuristics;

use Digidennis\Libdim\BinPacking\Box;
use Digidennis\Libdim\BinPacking\FreeSpaceBox;
use Digidennis\Libdim\BinPacking\Score;

class BestAreaFit extends Base {

    public function calculateScore(FreeSpaceBox $freeSpaceBox, Box $box ){
        if( !$box->isRotated() ){
            $areaFit = $freeSpaceBox->getWidth() * $freeSpaceBox->getHeight() - $box->getWidth() * $box->getHeight();
            $leftOverHorizontal = abs($freeSpaceBox->getWidth() - $box->getWidth());
            $leftOverVertical = abs($freeSpaceBox->getHeight() - $box->getHeight());
        } else {
            $areaFit = $freeSpaceBox->getWidth() * $freeSpaceBox->getHeight() - $box->getHeight() * $box->getWidth();
            $leftOverHorizontal = abs($freeSpaceBox->getWidth() - $box->getHeight());
            $leftOverVertical = abs($freeSpaceBox->getHeight() - $box->getWidth());
        }
        $shortSideFit = min([$leftOverHorizontal,$leftOverVertical]);
        return new Score($areaFit,$shortSideFit);
    }
}