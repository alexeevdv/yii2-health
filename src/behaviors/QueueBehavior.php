<?php

namespace alexeevdv\yii\health\behaviors;

use alexeevdv\yii\health\components\Queue as QueueComponent;
use yii\base\Behavior;
use yii\base\InvalidConfigException;
use yii\caching\CacheInterface;
use yii\di\Instance;
use yii\queue\Queue;

/**
 * Class QueueBehavior
 * // TODO description
 * @package alexeevdv\yii\health\behaviors
 */
class QueueBehavior extends Behavior
{
    /**
     * Cache component
     * @var CacheInterface|array|string
     */
    public $cache = 'cache';

    /**
     * Cache key for last executed job timestamp
     * @var string
     */
    public $lastExecutedJobCacheKey = QueueComponent::class;

    /**
     * @inheritDoc
     * @throws InvalidConfigException
     */
    public function init()
    {
        parent::init();
        $this->cache = Instance::ensure($this->cache, CacheInterface::class);
    }

    /**
     * @inheritDoc
     */
    public function events()
    {
        return [
            Queue::EVENT_AFTER_EXEC => 'onAfterExec',
        ];
    }

    /**
     * Stores last executed job timestamp to the cache
     */
    public function onAfterExec()
    {
        $this->cache->set($this->lastExecutedJobCacheKey, time());
    }
}
