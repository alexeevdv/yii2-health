<?php

namespace tests\unit;

use alexeevdv\yii\health\components\ComponentInterface;
use alexeevdv\yii\health\Module;
use Codeception\Test\Unit;
use yii\base\InvalidConfigException;

class ModuleTest extends Unit
{
    public function testComponentsAreEnsuredError()
    {
        $this->expectException(InvalidConfigException::class);
        new Module('id', null, [
            'components' => 123,
        ]);
    }

    public function testComponentsAreEnsuredSuccess()
    {
        new Module('id', null, [
            'components' => [
                $this->makeEmpty(ComponentInterface::class),
            ],
        ]);
    }
}
