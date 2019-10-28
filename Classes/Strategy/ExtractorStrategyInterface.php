<?php
/**
 * Created by PhpStorm.
 * User: digid
 * Date: 24-02-2019
 * Time: 11:02
 */

namespace Digidennis\Libdim\Strategy;


interface ExtractorStrategyInterface
{
    /**
     * @param array|null $dimensions
     * @param array|null $configuration
     * @return array
     * @throws \ErrorException
     */
    public function extract( array $dimensions=null, array $configuration=null );
}