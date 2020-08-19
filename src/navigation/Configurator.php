<?php
/**
 * @author Mamaev Yuriy (eXeCUT)
 * @link https://github.com/execut
 * @copyright Copyright (c) 2020 Mamaev Yuriy (eXeCUT)
 * @license http://www.apache.org/licenses/LICENSE-2.0
 */
namespace execut\crud\navigation;

use execut\crud\Translator;
use execut\navigation\Component;
use execut\navigation\Configurator as ConfiguratorInterface;
use yii\helpers\ArrayHelper;

/**
 * Configurator for navigation
 * @package execut\crud
 */
class Configurator implements ConfiguratorInterface
{
    /**
     * @var string Module id string
     */
    public $module = null;
    /**
     * @var string Module label in source language for translation
     */
    public $moduleName = null;
    /**
     * @var string Model label in english for translation
     */
    public $modelName = null;
    /**
     * @var string Controller id string
     */
    public $controller = null;
    /**
     * @var bool Is configure menu flag
     */
    public $isAddMenuItems = true;
    /**
     * @var array Parent pages list
     */
    public $pages = [];

    /**
     * Configure navigation
     * @param Component $navigation
     */
    public function configure(Component $navigation)
    {
        $url = '/' . $this->module . '/' . $this->controller . '/index';
        if ($this->isAddMenuItems) {
            $navigation->addMenuItem([
                'label' => $this->getTranslator()->getModuleLabel(),
                'url' => [
                    '/' . $this->module,
                ],
                'items' => [
                    [
                        'label' => $this->getTranslator()->getManyModelName(32),
                        'url' => [
                            $url,
                        ],
                    ],
                ]
            ]);
        }

        $currentModule = $this->getCurrentModule();
        if (!$currentModule || $currentModule !== $this->module) {
            return;
        }

        $controller = \yii::$app->controller;
        if (!$controller || $controller->id !== $this->controller) {
            return;
        }
        $pages = [
            'index' => [
                'name' => $this->getTranslator()->getManyModelName(32),
                'url' => [
                    $url,
                ],
            ],
        ];
        $action = $controller->action;
        if ($action->id === 'update') {
            $model = $action->adapter->model;
            if ($model->isNewRecord) {
                $name = $this->getTranslator()->getCreateLabel();
            } else {
                $name = $this->getTranslator()->getUpdateLabel();
            }

            $pages['update'] = [
                'name' => $name,
            ];
        }

        $pages = ArrayHelper::merge($this->pages, $pages);
        foreach ($pages as $page) {
            $navigation->addPage($page);
        }
    }

    /**
     * @return string Returns current module from application
     */
    protected function getCurrentModule()
    {
        if (!\Yii::$app->controller || !\Yii::$app->controller->module) {
            return null;
        }

        $currentModule = \Yii::$app->controller->module->id;
        return $currentModule;
    }

    /**
     * Returns CRUD translator
     * @return Translator
     */
    public function getTranslator()
    {
        return new Translator([
            'module' => $this->module,
            'modelName' => $this->modelName,
            'moduleName' => $this->moduleName,
        ]);
    }
}
