<?php

namespace tests\unit\components;

use alexeevdv\yii\health\components\Database;
use Codeception\Test\Unit;
use yii\db\Connection;
use yii\db\Exception;

class DatabaseTest extends Unit
{
    public function testGetType()
    {
        $component = new Database;
        $this->assertEquals('datastore', $component->getType());
    }

    public function testGetStatusWithMisconfiguredDbComponent()
    {
        $component = new Database([
            'db' => 123,
        ]);
        $this->assertEquals(Database::STATUS_FAIL, $component->getStatus());
        $this->assertEquals('Invalid data type: integer. yii\db\Connection is expected.', $component->getOutput());
    }

    public function testGetStatusWhenCantConnectToDatabase()
    {
        $component = new Database([
            'db' => $this->makeEmpty(Connection::class, [
                'open' => function () {
                    throw new Exception('Can not connect to database. Sorry.');
                },
            ]),
        ]);
        $this->assertEquals(Database::STATUS_FAIL, $component->getStatus());
        $this->assertEquals('Can not connect to database. Sorry.', $component->getOutput());
    }

    public function testGetStatusWhenConnectionIsInactive()
    {
        $component = new Database([
            'db' => $this->makeEmpty(Connection::class, [
                'getIsActive' => false,
            ]),
        ]);
        $this->assertEquals(Database::STATUS_FAIL, $component->getStatus());
        $this->assertEquals('', $component->getOutput());
    }

    public function testGetStatusWhenConnectionIsActive()
    {
        $component = new Database([
            'db' => $this->makeEmpty(Connection::class, [
                'getIsActive' => true,
            ]),
        ]);
        $this->assertEquals(Database::STATUS_PASS, $component->getStatus());
        $this->assertEquals('', $component->getOutput());
    }
}
