<?php
/**
 * Created by PhpStorm.
 * User: execut
 * Date: 7/19/17
 * Time: 12:45 PM
 */

namespace execut\crud;


use yii\base\BootstrapInterface;

class Bootstrap implements BootstrapInterface
{
    public function bootstrap($app)
    {
        $this->registerTranslations($app);
    }

    public function registerTranslations($app) {
        $app->i18n->translations['execut/crud/'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en-US',
            'basePath' => '@vendor/execut/yii2-crud/messages',
            'fileMap' => [
                'execut/crud/' => 'crud.php',
            ],
        ];
    }
}