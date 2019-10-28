<?php
/**
 * Created by PhpStorm.
 * User: digid
 * Date: 24-02-2019
 * Time: 11:05
 */

namespace Digidennis\Libdim\Strategy;


use Digidennis\Libdim\Objects\Rect;

class RectExtractorBoxCusionStrategy implements ExtractorStrategyInterface {

    const SeamWidth = 2;
    
    public function extract( array $dimensions = null, array $configuration = null) {

        if( count($dimensions) != 3 )
            throw new \ErrorException('Dimension mismatch' );

        $width = $dimensions['width'];
        $depth = $dimensions['depth'];
        $height = $dimensions['height'];

        if(key_exists('opirulle', $configuration) && $configuration['opirulle'] === '1') {
            $rects = [
                'top' => new Rect($depth + self::SeamWidth,$width + self::SeamWidth),
                'bottom' => new Rect($depth + self::SeamWidth, $width + self::SeamWidth),
                'front' => new Rect($height + self::SeamWidth, $width + self::SeamWidth),
                'back' => new Rect($height + self::SeamWidth,$width + self::SeamWidth),
                'left' => new Rect($depth + self::SeamWidth,$height + self::SeamWidth),
                'right' => new Rect($depth + self::SeamWidth,$height + self::SeamWidth)
            ];
        } else {
            $rects = [
                'top' => new Rect($width + self::SeamWidth,$depth + self::SeamWidth),
                'bottom' => new Rect($width + self::SeamWidth, $depth + self::SeamWidth),
                'front' => new Rect($width + self::SeamWidth,$height + self::SeamWidth),
                'back' => new Rect($width + self::SeamWidth,$height + self::SeamWidth),
                'left' => new Rect($height + self::SeamWidth, $depth + self::SeamWidth),
                'right' => new Rect($height + self::SeamWidth, $depth + self::SeamWidth)
            ];
        }
        if( count($configuration) ){
            if(key_exists('underspejl', $configuration) && $configuration['underspejl'] === '1') {
                $transformedBottom = $rects['bottom'];
                $spejlkant = floatval($configuration['spejlkant']);
                if($spejlkant > 0 ) {
                    if( $spejlkant >= $depth*.5 || $spejlkant >= $width*.5)
                        throw new \ErrorException('Configuration mismatch' );
                    $rects['spejlkanttop'] = new Rect($rects['bottom']->getX(), $spejlkant + self::SeamWidth);
                    $rects['spejlkantbottom'] = new Rect($rects['bottom']->getX(), $spejlkant + self::SeamWidth);
                    $rects['spejlkantleft'] = new Rect($spejlkant + self::SeamWidth,$rects['bottom']->getY() - ($spejlkant*2) );
                    $rects['spejlkantright'] = new Rect($spejlkant + self::SeamWidth,$rects['bottom']->getY() - ($spejlkant*2) );
                }
                unset($rects['bottom']);
            }
            if(key_exists('treetbund', $configuration) && $configuration['treetbund'] === '1') {
                unset($rects['front']);
                unset($rects['left']);
                unset($rects['right']);
                $rects['treetbund'] = new Rect($height + self::SeamWidth, $width+$depth+$depth + self::SeamWidth );
            }
        }
        return $rects;
    }
}