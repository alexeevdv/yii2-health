<?php

namespace tests\unit\controllers;

use alexeevdv\yii\health\controllers\DefaultController;
use alexeevdv\yii\health\Module;
use alexeevdv\yii\health\Response;
use Codeception\Test\Unit;
use Yii;
use yii\web\Application;

class DefaultControllerTest extends Unit
{
    public function testActionIndex()
    {
        Yii::$app = $this->makeEmpty(Application::class);
        $controller = new DefaultController('id', $this->make(Module::class, [
            'components' => [1, 2],
        ]));
        $response = $controller->actionIndex();
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals([1, 2], $response->data);
    }
}
