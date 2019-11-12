<?php

namespace alexeevdv\yii\health;

use alexeevdv\yii\health\components\ComponentInterface;
use yii\base\InvalidConfigException;
use yii\di\Instance;

/**
 * Class Module
 * @package alexeevdv\yii\health
 */
class Module extends \yii\base\Module
{
    /**
     * Array of component configurations
     * @var ComponentInterface[]|array
     */
    public $components = [];

    /**
     * @inheritDoc
     */
    public $defaultRoute = 'default/index';

    /**
     * @throws InvalidConfigException
     * @inheritDoc
     */
    public function init()
    {
        parent::init();
        $this->components = array_map(function ($componentConfig) {
            return Instance::ensure($componentConfig, ComponentInterface::class);
        }, (array) $this->components);
    }
}
