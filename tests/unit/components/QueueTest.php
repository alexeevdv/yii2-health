<?php

namespace tests\unit\components;

use alexeevdv\yii\health\components\Queue;
use Codeception\Test\Unit;
use yii\base\InvalidConfigException;
use yii\caching\CacheInterface;

class QueueTest extends Unit
{
    public function testCacheIsEnsured()
    {
        $this->expectException(InvalidConfigException::class);
        new Queue([
            'cache' => 123,
        ]);
    }

    public function testGetType()
    {
        $component = new Queue([
            'cache' => $this->makeEmpty(CacheInterface::class),
        ]);
        $this->assertEquals('component', $component->getType());
    }

    public function testGetStatusWhenNoJobsWereExecutedYet()
    {
        $component = new Queue([
            'cache' => $this->makeEmpty(CacheInterface::class, [
                'get' => function ($key) {
                    $this->assertEquals(Queue::class, $key);
                    return false;
                }
            ]),
        ]);
        $this->assertEquals(Queue::STATUS_WARN, $component->getStatus());
        $this->assertEquals('No jobs were executed yet', $component->getOutput());
    }

    public function testGetStatusWhenFailoverTimeoutPassed()
    {
        $component = new Queue([
            'cache' => $this->makeEmpty(CacheInterface::class, [
                'get' => function () {
                    return time() - 400;
                }
            ]),
        ]);
        $this->assertEquals(Queue::STATUS_FAIL, $component->getStatus());
        $this->assertStringStartsWith('Seconds since last executed job:', $component->getOutput());
    }

    public function testGetStatusWhenFailoverTimeoutNotPassed()
    {
        $component = new Queue([
            'cache' => $this->makeEmpty(CacheInterface::class, [
                'get' => function () {
                    return time() - 10;
                }
            ]),
        ]);
        $this->assertEquals(Queue::STATUS_PASS, $component->getStatus());
        $this->assertEquals('', $component->getOutput());
    }
}
