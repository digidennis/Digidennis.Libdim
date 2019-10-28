<?php
/**
 * Created by PhpStorm.
 * User: digid
 * Date: 27-03-2019
 * Time: 22:32
 */

namespace Digidennis\Libdim\Objects;


class DimensionalUnit
{
    /**
     * @var string $strategy
     */
    protected $strategy;
    /**
     * @var string $label
     */
    protected $label;
    /**
     * @var array $dimensions
     */
    protected $dimensions;
    /**
     * @var array $config
     */
    protected $config;
    /**
     * @var bool $rotate
     */
    protected $rotate;

    /**
     * DimensionalUnit constructor.
     * @param string $strategy
     * @param string $label
     * @param array $dimensions
     * @param array $config
     * @param bool $rotate
     */
    public function __construct( $strategy, $label, $dimensions=[], $config=[], $rotate=false)
    {
        $this->setStrategy($strategy);
        $this->setLabel($label);
        $this->setDimensions($dimensions);
        $this->setConfig($config);
        $this->setRotate($rotate);
    }

    /**
     * @param string $strategy
     */
    public function setStrategy(string $strategy)
    {
        $this->strategy = $strategy;
    }

    /**
     * @return string
     */
    public function getStrategy(): string
    {
        return $this->strategy;
    }
    /**
     * @return string
     */
    public function getLabel(): string
    {
        return $this->label;
    }

    /**
     * @param string $label
     */
    public function setLabel(string $label)
    {
        $this->label = $label;
    }
    /**
     * @return array
     */
    public function getConfig(): array
    {
        return $this->config;
    }
    /**
     * @return array
     */
    public function getDimensions(): array
    {
        return $this->dimensions;
    }
    /**
     * @return bool
     */
    public function isRotate(): bool
    {
        return $this->rotate;
    }
    /**
     * @param array $config
     */
    public function setConfig(array $config)
    {
        $this->config = $config;
    }
    /**
     * @param array $dimensions
     */
    public function setDimensions(array $dimensions)
    {
        $this->dimensions = $dimensions;
    }
    /**
     * @param bool $rotate
     */
    public function setRotate(bool $rotate)
    {
        $this->rotate = $rotate;
    }
}