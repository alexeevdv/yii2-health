<?php

namespace alexeevdv\yii\health\components;

/**
 * Interface ComponentInterface
 * @package alexeevdv\yii\health\components
 */
interface ComponentInterface
{
    const STATUS_PASS = 1;
    const STATUS_FAIL = 2;
    const STATUS_WARN = 3;

    /**
     * Returns type of component
     * @see https://tools.ietf.org/html/draft-inadarei-api-health-check-03#section-4.2
     * @return string
     */
    public function getType(): string;

    /**
     * Returns component status
     * @see https://tools.ietf.org/html/draft-inadarei-api-health-check-03#section-4.5
     * @return int
     */
    public function getStatus(): int;

    /**
     * Returns component output
     * @see https://tools.ietf.org/html/draft-inadarei-api-health-check-03#section-4.8
     * @return string
     */
    public function getOutput(): string;
}
