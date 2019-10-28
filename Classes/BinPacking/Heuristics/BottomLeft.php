<?php
/**
 * Created by PhpStorm.
 * User: digid
 * Date: 02-03-2019
 * Time: 12:14
 */

namespace Digidennis\Libdim\BinPacking\Heuristics;

use Digidennis\Libdim\BinPacking\Box;
use Digidennis\Libdim\BinPacking\FreeSpaceBox;
use Digidennis\Libdim\BinPacking\Score;

class BottomLeft extends Base {

    public function calculateScore(FreeSpaceBox $freeSpaceBox, Box $box){
        $topSideY = $freeSpaceBox->getY() + $freeSpaceBox->getHeight();
        return new Score($topSideY,$freeSpaceBox->getX());
    }
}