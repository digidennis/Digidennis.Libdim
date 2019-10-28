<?php
/**
 * Created by PhpStorm.
 * User: digid
 * Date: 01-03-2019
 * Time: 18:39
 */

namespace Digidennis\Libdim\BinPacking;


class Score{
    /**
     * @var int $score1,$score2;
     */
    public $score1, $score2;

    /**
     * Score constructor.
     * @param int $score1
     * @param int $score2
     */
    public function __construct( int $score1=PHP_INT_MAX, int $score2=PHP_INT_MAX ){
        $this->score1 = $score1;
        $this->score2 = $score2;
    }
    /**
     * @param Score $other
     * @return int
     */
    public function compare(Score $other){
        if($this->score1 > $other->score1 || ($this->score1 == $other->score1 && $this->score2 > $other->score2 ))
            return -1;
        elseif ($this->score1 < $other->score1 || ($this->score1 == $other->score1 && $this->score2 < $other->score2 ))
            return 1;
        else
            return 0;
    }
    /**
     * @param Score $other
     */
    public function assign(Score $other){
        $this->score1 = $other->score1;
        $this->score2 = $other->score2;
    }
    /**
     * @return bool
     */
    public function isBlank(){
        return $this->score1 === PHP_INT_MAX;
    }
    /**
     * @param int $delta
     */
    public function decreaseBy( int $delta ){
        $this->score1 += $delta;
        $this->score2 += $delta;
    }
}