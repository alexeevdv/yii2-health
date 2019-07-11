<?php

namespace alexeevdv\yii\health\components;

use DateTime;
use DateTimeInterface;
use yii\base\BaseObject;
use yii\base\InvalidConfigException;
use yii\caching\CacheInterface;
use yii\di\Instance;

/**
 * Class Queue
 * // TODO description
 * @package alexeevdv\yii\health\components
 */
class Queue extends BaseObject implements ComponentInterface
{
    /**
     * @var CacheInterface|array|string
     */
    public $cache = 'cache';

    /**
     * Cache key for last executed job timestamp
     * @var string
     */
    public $lastExecutedJobCacheKey = self::class;

    /**
     * Second from last executed job for queue to be reported as failed
     * @var int
     */
    public $failoverTimeout = 300;

    /**
     * @var string
     */
    private $_output = '';

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
    public function getType(): string
    {
        return 'component';
    }

    /**
     * @inheritDoc
     */
    public function getStatus(): int
    {
        $lastExecutedAt = $this->getLastExecutedAt();
        if ($lastExecutedAt === null) {
            $this->_output = 'No jobs were executed yet';
            return self::STATUS_WARN;
        }

        $currentTime = new DateTime;
        $secondsSinceLastJob = $currentTime->getTimestamp() - $lastExecutedAt->getTimestamp();
        if ($secondsSinceLastJob >= $this->failoverTimeout) {
            $this->_output = 'Seconds since last executed job: ' . $secondsSinceLastJob;
            return self::STATUS_FAIL;
        }
        return self::STATUS_PASS;
    }

    /**
     * @inheritDoc
     */
    public function getOutput(): string
    {
        return $this->_output;
    }

    private function getLastExecutedAt(): ?DateTimeInterface
    {
        $lastExecutedAt = DateTime::createFromFormat('U', $this->cache->get($this->lastExecutedJobCacheKey));
        return $lastExecutedAt ? $lastExecutedAt : null;
    }
}
