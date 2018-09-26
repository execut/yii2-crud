<?php
/**
 */

namespace execut\crud\navigation;


use execut\crud\Translator;
use execut\navigation\Component;
use execut\navigation\Configurator as ConfiguratorInterface;
use execut\navigation\page\Home;
use yii\helpers\ArrayHelper;
use yii\helpers\Inflector;

class Configurator implements ConfiguratorInterface
{
    public $module = null;
    public $moduleName = null;
    public $modelName = null;
    public $controller = null;
    public $pages = [];
    public function configure(Component $navigation)
    {
        $url = '/' . $this->module . '/' . $this->controller;
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
     * @return string
     */
    protected function getCurrentModule()
    {
        if (!\Yii::$app->controller || !\Yii::$app->controller->module) {
            return;
        }

        $currentModule = \Yii::$app->controller->module->id;
        return $currentModule;
    }

    public function getTranslator() {
        return new Translator([
            'module' => $this->module,
            'modelName' => $this->modelName,
            'moduleName' => $this->moduleName,
        ]);
    }
}