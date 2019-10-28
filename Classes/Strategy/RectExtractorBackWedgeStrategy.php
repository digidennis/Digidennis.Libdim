<?php
/**
 * Created by PhpStorm.
 * User: digid
 * Date: 24-02-2019
 * Time: 11:05
 */

namespace Digidennis\Libdim\Strategy;


use Digidennis\Libdim\Objects\Rect;

class RectExtractorBackWedgeStrategy implements ExtractorStrategyInterface {

    const TopFrontSlack = 3;
    const SeamWidth = 2;
    
    public function extract( array $dimensions = null, array $configuration = null) {

        if( count($dimensions) != 4 )
            throw new \ErrorException('Dimension mismatch' );

        $width = $dimensions['width'];
        $bottomdepth = $dimensions['bottomdepth'];
        $topdepth = $dimensions['topdepth'];
        $height = $dimensions['height'];

        if($bottomdepth < $topdepth ) {
            $buffer = $bottomdepth;
            $bottomdepth = $topdepth;
            $topdepth = $buffer;
        }

        $b_triline = $bottomdepth-$topdepth;
        $c_triline = floor(sqrt(($b_triline * $b_triline)+($height*$height)));

        if(key_exists('opirulle', $configuration) && $configuration['opirulle'] === '1') {
            $rects = [
                'top' => new Rect($topdepth+self::SeamWidth,$width+self::SeamWidth),
                'bottom' => new Rect($bottomdepth+self::SeamWidth,$width+self::SeamWidth),
                'front' => new Rect($c_triline+self::SeamWidth,$width+self::SeamWidth),
                'back' => new Rect($height+self::SeamWidth,$width+self::SeamWidth),
                'left' => new Rect($height+self::SeamWidth,$bottomdepth+self::SeamWidth),
                'right' => new Rect($height+self::SeamWidth,$bottomdepth+self::SeamWidth)
            ];
        } else {
            $rects = [
                'top' => new Rect($width+self::SeamWidth,$topdepth+self::SeamWidth),
                'bottom' => new Rect($width+self::SeamWidth, $bottomdepth+self::SeamWidth),
                'front' => new Rect($width+self::SeamWidth,$c_triline+self::SeamWidth),
                'back' => new Rect($width+self::SeamWidth,$height+self::SeamWidth),
                'left' => new Rect($bottomdepth+self::SeamWidth,$height+self::SeamWidth),
                'right' => new Rect($bottomdepth+self::SeamWidth,$height+self::SeamWidth)
            ];
        }

        if( count($configuration) ){
            if(key_exists('underspejl', $configuration) && $configuration['underspejl'] === '1') {
                $transformedrect = $rects['bottom'];
                $spejlkant = floatval($configuration['underspejlkant']);

                if($spejlkant > 0 ) {
                    if( $spejlkant >= $bottomdepth*.5 || $spejlkant >= $width*.5)
                        throw new \ErrorException('Configuration mismatch' );

                    $rects['underspejlkanttop'] = new Rect($transformedrect->getX(), $spejlkant + self::SeamWidth);
                    $rects['underspejlkantbottom'] = new Rect($transformedrect->getX(), $spejlkant + self::SeamWidth);
                    $rects['underspejlkantleft'] = new Rect($spejlkant + self::SeamWidth,$transformedrect->getY()-($spejlkant*2));
                    $rects['underspejlkantright'] = new Rect($spejlkant + self::SeamWidth,$transformedrect->getY()-($spejlkant*2));
                }
                unset($rects['bottom']);
            }
            if(key_exists('bagspejl', $configuration) && $configuration['bagspejl'] === '1') {
                $transformedrect = $rects['back'];
                $spejlkant = floatval($configuration['bagspejlkant']);
                if($spejlkant > 0 ) {
                    if( $spejlkant >= $bottomdepth*.5 || $spejlkant >= $width*.5)
                        throw new \ErrorException('Configuration mismatch' );

                    $rects['bagspejlkanttop'] = new Rect($transformedrect->getX(), $spejlkant + self::SeamWidth);
                    $rects['bagspejlkantbottom'] = new Rect($transformedrect->getX(), $spejlkant + self::SeamWidth);
                    $rects['bagspejlkantleft'] = new Rect($spejlkant + self::SeamWidth,$transformedrect->getY()-($spejlkant*2));
                    $rects['bagspejlkantright'] = new Rect($spejlkant + self::SeamWidth,$transformedrect->getY()-($spejlkant*2));
                }
                unset($rects['back']);
            }
            if(key_exists('topfront', $configuration) && $configuration['topfront'] === '1') {
                $c_triline = floor(sqrt(($bottomdepth*$bottomdepth)+($height*$height)));
                if(key_exists('opirulle', $configuration) && $configuration['opirulle'] === '1') {
                    $rects['topfront'] = new Rect($c_triline + $topdepth - self::TopFrontSlack + self::SeamWidth, $width + self::SeamWidth);
                } else {
                    $rects['topfront'] = new Rect($width + self::SeamWidth, $c_triline + $topdepth - self::TopFrontSlack + self::SeamWidth);
                }
                unset($rects['front']);
                unset($rects['top']);
            }
        }
        return $rects;
    }
}