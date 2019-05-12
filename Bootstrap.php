<?php
/**
 * Created by PhpStorm.
 * User: execut
 * Date: 7/19/17
 * Time: 12:45 PM
 */

namespace execut\crud;

class Bootstrap extends \execut\yii\Bootstrap
{
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

    public function bootstrap($app)
    {
        parent::bootstrap($app);
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