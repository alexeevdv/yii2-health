yii2-health
==========

[![Build Status](https://travis-ci.com/alexeevdv/yii2-health.svg?branch=master)](https://travis-ci.com/alexeevdv/yii2-health) 
[![codecov](https://codecov.io/gh/alexeevdv/yii2-health/branch/master/graph/badge.svg)](https://codecov.io/gh/alexeevdv/yii2-health)
![PHP 7.1](https://img.shields.io/badge/PHP-7.1-green.svg) 
![PHP 7.2](https://img.shields.io/badge/PHP-7.2-green.svg)
![PHP 7.3](https://img.shields.io/badge/PHP-7.3-green.svg)


Yii2 module for application health status reporting


Installation:
-------------

The preferred way to install this extension is through [composer](https://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist alexeevdv/yii2-health "^1.0"
```

or add

```
"alexeevdv/yii2-health": "^1.0"
```

to the require section of your composer.json.


Configuration:
--------------

```php
//...
    'modules' => [
        'health' => [
            'class' => alexeevdv\yii\health\Module::class,
            'components' => [
                'database' => alexeevdv\yii\health\components\Database::class,
                'queue' => [
                    'class' => alexeevdv\yii\health\components\Queue::class,
                    'failoverTimeout' => 600, // default is 300
                ],
            ],
        ],
    ],
    'components' => [
        // ...
            'queue' => [
                // Add this to enable last executed job timestamp logging
                'as health' => alexeevdv\yii\health\behaviors\QueueBehavior::class,
            ],
            'urlManager' => [
                'rules' => [
                    // Add url rule to access health status report
                    'api/v1/health' => '/health',
                ],
            ],
        // ...
        
    ],
//...

```

Usage:
------

```
$ curl http://localhost/api/v1/health
{
    "status": "warn",
    "checks": {
        "database": [
            {
                "type": "datastore",
                "status": "pass",
                "time": "2019-07-09T07:32:10+0000",
                "output": ""
            }
        ],
        "queue": [
            {
                "type": "component",
                "status": "warn",
                "time": "2019-07-09T07:32:10+0000",
                "output": "No jobs were executed yet"
            }
        ]
    }
}
```

Supported components:
--------------------

* Database

  Class: alexeevdv\yii\health\Database
  Params:
  * `db` - Database component configuration

* Queue
  
  Class: alexeevdv\yii\health\Queue
  Params:
  * `cache` - Cache component configuration
  * `lastExecutedJobCacheKey` - Cache key for last executed job timestamp
  * `failoverTimeout` - Second from last executed job for queue to be reported as failed
