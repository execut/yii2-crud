<?php
/**
 * @author Mamaev Yuriy (eXeCUT)
 * @link https://github.com/execut
 * @copyright Copyright (c) 2020 Mamaev Yuriy (eXeCUT)
 * @license http://www.apache.org/licenses/LICENSE-2.0
 */
namespace execut\crud;

/**
 * Base bootstrap class for CRUD
 * @package execut\crud
 */
class Bootstrap extends \execut\yii\Bootstrap
{
    /**
     * {@inheritDoc}
     */
    public $isBootstrapI18n = true;
    /**
     * {@inheritDoc}
     */
    public function getDefaultDepends()
    {
        return [
            'bootstrap' => [
                'yii2-actions' => [
                    'class' => \execut\actions\Bootstrap::class,
                ],
            ],
        ];
    }
}
