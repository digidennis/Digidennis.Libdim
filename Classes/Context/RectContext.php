<?php
/**
 * Created by PhpStorm.
 * User: digid
 * Date: 24-02-2019
 * Time: 11:07
 */

namespace Digidennis\Libdim\Context;

use Digidennis\Libdim\Strategy\RectExtractorBackWedgeStrategy;
use Digidennis\Libdim\Strategy\RectExtractorBoxCusionStrategy;

class RectContext{

    /**
     * @var array $strategies
     */
    protected $strategies;

    public function __construct(){
        $this->strategies['BoxCushion'] = new RectExtractorBoxCusionStrategy();
        $this->strategies['BackWedge'] = new RectExtractorBackWedgeStrategy();
    }

    /**
     * @param array|null $dimensions
     * @param array|null $configuration
     * @param string $type
     * @return array
     */
    public function extract( string $type, array $dimensions=null, array $configuration=null ){
        return $this->strategies[$type]->extract($dimensions, $configuration);
    }

    public function getStrategies() {
        return $this->strategies;
    }
}