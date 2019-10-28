<?php
/**
 * Created by PhpStorm.
 * User: digid
 * Date: 24-02-2019
 * Time: 11:53
 */

namespace Digidennis\Libdim\Command;

use Digidennis\Libdim\BinPacking\Box;
use Neos\Flow\Cli\CommandController;
use Neos\Flow\Annotations as Flow;
use Digidennis\Libdim\Context\RectContext;
use Digidennis\Libdim\BinPacking\Bin;

/**
 * @Flow\Scope("singleton")
 */
class TestCommandController extends CommandController {

    /**
     * Brew some coffee
     *
     * This command brews the specified type and amount of coffee.
     *
     * Make sure to specify a type which best suits the kind of drink
     * you're aiming for. Some types are better suited for a Latte, while
     * others make a perfect Espresso.
     */
    public function TestRectCommand(){
        $context = new RectContext();
        $results = $context->extract([70,58,10], 'BoxCusion');
        foreach($results as $result){
            $this->outputline( key($results) .': '. $result->getX() . ', ' . $result->getY() );
            next($results);
        }
    }

    public function BinTestCommand(){
        $bin = new Bin(137, 2500 );
        $bin->insert(new Box(47,182) );
        $bin->insert(new Box(47,182) );
        $bin->insert(new Box(47,182) );
        $bin->insert(new Box(47,182) );
        $bin->insert(new Box(135,182) );
        $this->outputline('Bin boxes: ' . count($bin->boxes) );
        $this->outputline('Efficiency: ' . $bin->efficiency() );
    }
}