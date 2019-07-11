<?php

namespace common\tests\behaviors;

use alexeevdv\yii\health\behaviors\QueueBehavior;
use Codeception\Stub\Expected;
use Codeception\Test\Unit;
use yii\base\InvalidConfigException;
use yii\caching\CacheInterface;
use yii\queue\sync\Queue;

class QueueBehaviorTest extends Unit
{
    public function testCacheIsEnsured()
    {
        $this->expectException(InvalidConfigException::class);
        new QueueBehavior([
            'cache' => 123,
        ]);
    }

    public function testTimestampIsStoredWhenJobIsExecuted()
    {
        $behavior = new QueueBehavior([
            'lastExecutedJobCacheKey' => 'myKey',
            'cache' => $this->makeEmpty(CacheInterface::class, [
                'set' => Expected::once(function ($key, $value) {
                    $this->assertEquals('myKey', $key);
                    $this->assertIsNumeric($value);
                }),
            ]),
        ]);

        $queue = new Queue;
        $queue->attachBehavior('queue', $behavior);
        $queue->trigger(Queue::EVENT_AFTER_EXEC);
    }
}
