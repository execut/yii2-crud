<?php
namespace execut\crud\bootstrap;

use execut\yii\Bootstrap;

class Console extends Bootstrap
{
    protected $_defaultDepends = [
        'bootstrap' => [
            'yii2-actions' => [
                'class' => \execut\actions\bootstrap\Console::class,
            ]
        ]
    ];
}