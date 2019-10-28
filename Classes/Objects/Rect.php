<?php
/**
 * Created by PhpStorm.
 * User: digid
 * Date: 24-02-2019
 * Time: 10:57
 */

namespace Digidennis\Libdim\Objects;


class Rect
{
    /**
     * @var float $x,$y
     */
    protected $x,$y;

    public function __construct( float $x, float $y){
        $this->x = $x;
        $this->y = $y;
    }

    /**
     * @return float
     */
    public function getX()
    {
        return $this->x;
    }

    /**
     * @return float
     */
    public function getY()
    {
        return $this->y;
    }
}