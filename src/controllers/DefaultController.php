<?php

namespace alexeevdv\yii\health\controllers;

use alexeevdv\yii\health\Response;
use yii\web\Controller;

/**
 * Class DefaultController
 * @package alexeevdv\yii\health\controllers
 */
class DefaultController extends Controller
{
    /**
     * @return Response
     */
    public function actionIndex()
    {
        return new Response([
            'data' => $this->module->components,
        ]);
    }
}
