<?php

namespace alexeevdv\yii\health\components;

use yii\base\BaseObject;
use yii\base\InvalidConfigException;
use yii\db\Connection;
use yii\db\Exception;
use yii\di\Instance;

/**
 * Class Database
 * // TODO description
 * @package alexeevdv\yii\health\components
 */
class Database extends BaseObject implements ComponentInterface
{
    /**
     * Database component
     * @var Connection|array|string
     */
    public $db = 'db';

    /**
     * @var string
     */
    private $_output = '';

    /**
     * @inheritDoc
     */
    public function getType(): string
    {
        return 'datastore';
    }

    /**
     * @inheritDoc
     */
    public function getStatus(): int
    {
        try {
            /** @var Connection $database */
            $database = Instance::ensure($this->db, Connection::class);
        } catch (InvalidConfigException $e) {
            $this->_output = $e->getMessage();
            return self::STATUS_FAIL;
        }

        try {
            $database->open();
        } catch (Exception $e) {
            $this->_output = $e->getMessage();
            return self::STATUS_FAIL;
        }

        return $database->getIsActive() ? self::STATUS_PASS : self::STATUS_FAIL;
    }

    /**
     * @inheritDoc
     */
    public function getOutput(): string
    {
        return $this->_output;
    }
}
