<?php

namespace alexeevdv\yii\health;

use alexeevdv\yii\health\components\ComponentInterface;
use DateTime;
use yii\web\JsonResponseFormatter;

/**
 * Class ResponseFormatter
 * @package alexeevdv\yii\health
 */
class ResponseFormatter extends JsonResponseFormatter
{
    const CONTENT_TYPE_HEALTH = 'application/health+json';

    /**
     * @inheritdoc
     */
    public $contentType = self::CONTENT_TYPE_HEALTH;

    /**
     * @inheritDoc
     */
    public function format($response)
    {
        $data = [
            'status' => 'pass',
            'checks' => [],
        ];

        $globalStatus = ComponentInterface::STATUS_PASS;

        /**
         * @var string $componentName
         * @var ComponentInterface $component
         */
        foreach ($response->data as $componentName => $component) {
            $status = $component->getStatus();
            if ($status === ComponentInterface::STATUS_FAIL) {
                $globalStatus = ComponentInterface::STATUS_FAIL;
                $response->setStatusCode(503);
            }
            if ($status === ComponentInterface::STATUS_WARN && $globalStatus !== ComponentInterface::STATUS_FAIL) {
                $globalStatus = ComponentInterface::STATUS_WARN;
                $response->setStatusCode(207);
            }

            $data['checks'][$componentName][] = [
                'type' => $component->getType(),
                'status' => $this->mapStatus($status),
                'time' => (new DateTime)->format(DateTime::ISO8601),
                'output' => $component->getOutput(),
            ];
        }

        $data['status'] = $this->mapStatus($globalStatus);

        $response->data = $data;
        parent::format($response);
    }

    private function mapStatus(int $status): string
    {
        if ($status === ComponentInterface::STATUS_PASS) {
            return 'pass';
        }

        if ($status === ComponentInterface::STATUS_WARN) {
            return 'warn';
        }

        return 'fail';
    }
}
