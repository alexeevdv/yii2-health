<?php

namespace tests\unit;

use alexeevdv\yii\health\components\ComponentInterface;
use alexeevdv\yii\health\Response;
use alexeevdv\yii\health\ResponseFormatter;
use Codeception\Test\Unit;

class ResponseFormatterTest extends Unit
{
    public function testStatusIsFailIfOneOfComponentsIsFailed()
    {
        $response = $this->make(Response::class, [
            'data' => [
                $this->makeEmpty(ComponentInterface::class, [
                    'getStatus' => ComponentInterface::STATUS_PASS,
                ]),
                $this->makeEmpty(ComponentInterface::class, [
                    'getStatus' => ComponentInterface::STATUS_WARN,
                ]),
                $this->makeEmpty(ComponentInterface::class, [
                    'getStatus' => ComponentInterface::STATUS_FAIL,
                ]),
            ],
        ]);
        (new ResponseFormatter)->format($response);
        $this->assertEquals('fail', $response->data['status']);
    }

    public function testStatusIsWarnIfOneOfComponentsHasWarning()
    {
        $response = $this->make(Response::class, [
            'data' => [
                $this->makeEmpty(ComponentInterface::class, [
                    'getStatus' => ComponentInterface::STATUS_PASS,
                ]),
                $this->makeEmpty(ComponentInterface::class, [
                    'getStatus' => ComponentInterface::STATUS_WARN,
                ]),
                $this->makeEmpty(ComponentInterface::class, [
                    'getStatus' => ComponentInterface::STATUS_PASS,
                ]),
            ],
        ]);
        (new ResponseFormatter)->format($response);
        $this->assertEquals('warn', $response->data['status']);
    }

    public function testStatusIsPassIfAllComponentsArePassed()
    {
        $response = $this->make(Response::class, [
            'data' => [
                $this->makeEmpty(ComponentInterface::class, [
                    'getStatus' => ComponentInterface::STATUS_PASS,
                ]),
                $this->makeEmpty(ComponentInterface::class, [
                    'getStatus' => ComponentInterface::STATUS_PASS,
                ]),
                $this->makeEmpty(ComponentInterface::class, [
                    'getStatus' => ComponentInterface::STATUS_PASS,
                ]),
            ],
        ]);
        (new ResponseFormatter)->format($response);
        $this->assertEquals('pass', $response->data['status']);
    }
}
