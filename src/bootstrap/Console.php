<?php
/**
 * @author Mamaev Yuriy (eXeCUT)
 * @link https://github.com/execut
 * @copyright Copyright (c) 2020 Mamaev Yuriy (eXeCUT)
 * @license http://www.apache.org/licenses/LICENSE-2.0
 */
namespace execut\crud\bootstrap;

use execut\yii\Bootstrap;

/**
 * Class Console
 * @package execut\crud
 */
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
