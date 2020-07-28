<?php
/**
 * @author Mamaev Yuriy (eXeCUT)
 * @link https://github.com/execut
 * @copyright Copyright (c) 2020 Mamaev Yuriy (eXeCUT)
 * @license http://www.apache.org/licenses/LICENSE-2.0
 */
namespace execut\crud;

/**
 * Class Bootstrap
 * @package execut\crud
 */
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

    public function registerTranslations($app)
    {
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
