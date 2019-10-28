<?php
/**
 * Created by PhpStorm.
 * User: digid
 * Date: 02-03-2019
 * Time: 00:36
 */
namespace Digidennis\Libdim\Controller;

use Digidennis\Libdim\Objects\BinPackHelper;
use Digidennis\Libdim\Form\ContactformFactory;
use Digidennis\Libdim\Objects\DimensionalUnit;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\Mvc\Controller\ActionController;


class BinPackController extends ActionController {
    /**
     * @Flow\Inject
     * @var BinPackHelper
     */
    protected $binPackHelper;
    /**    /**
     * @Flow\Inject
     * @var ContactformFactory
     */
    protected $contactformFactory;
    /**
     * Index action
     */
    public function indexAction() {
        $this->view->assign('helper', $this->binPackHelper);
        $this->view->assign('zoom', 8);
    }

    /**
     * @param  int $width
     * @param  int $height
     * @param  int $count
     * @param  bool $canrotate
     * @return void
     */
    public function addrectAction( $width,  $height,  $count,  $canrotate ){
        $this->binPackHelper->addBox($width,$height,$count,$canrotate);
        $this->redirect('index');
    }

    /**
     * @param  int $count
     * @param string $strategy
     * @param array $dimensions
     * @param array $config
     * @param string $label
     * @param bool $rotate
     * @return void
     * @throws \Exception
     */
    public function adddimensionalunitAction( int $count=1, string $strategy, array $dimensions,  array $config, string $label, bool $rotate){
        for($i = 0;$i<$count;$i++){
            $this->binPackHelper->addDimensionalUnit(
                new DimensionalUnit(
                    $strategy,
                    $label . '_' . $i,
                    $dimensions,
                    $config,
                    $rotate
                )
            );
        }
        $this->redirect('index');
    }
    /**
     * @param  int $index
     * @return void
     */
    public function removerectAction( $index ){
        $this->binPackHelper->removeBox($index);
        $this->redirect('index');
    }
    /**
     * @param  int $index
     * @return void
     */
    public function removedimensionalunitAction( $index ){
        $this->binPackHelper->removeDimensionalUnit($index);
        $this->redirect('index');
    }
    /**
     * @param int $width
     * @return void
     */
    public function packAction( int $width ){
        $this->binPackHelper->packBoxes($width);
        $this->redirect('index');
    }

    /**
     * @return void
     */
    public function resetAction( ){
        $this->binPackHelper->reset();
        $this->redirect('index');
    }

    public function emailAction(){
        $form = $this->contactformFactory->build([],'default');
        $this->view->assign('form', $form );
    }

}