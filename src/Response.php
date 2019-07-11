<?php

namespace alexeevdv\yii\health;

/**
 * Class Response
 * @package alexeevdv\yii\health
 */
class Response extends \yii\web\Response
{
    const FORMAT_HEALTH = 'health';

    /**
     * @inheritDoc
     */
    public $format = self::FORMAT_HEALTH;

    /**
     * @inheritDoc
     */
    public $formatters = [
        self::FORMAT_HEALTH => ResponseFormatter::class,
    ];
}
