<?php
/**
 */

namespace execut\crud\navigation;


use execut\navigation\Component;
use execut\navigation\Configurator as ConfiguratorInterface;
use execut\navigation\page\Home;
use yii\helpers\Inflector;

class Configurator implements ConfiguratorInterface
{
    public $module = null;
    public $moduleName = null;
    public $modelName = null;
    public $controller = null;
    public function configure(Component $navigation)
    {
        $url = '/' . $this->module . '/' . $this->controller;
        $navigation->addMenuItem([
            'label' => $this->moduleName,
            'url' => [
                '/' . $this->module,
            ],
            'items' => [
                [
                    'label' => $this->getManyModelName(),
                    'url' => [
                        $url,
                    ],
                ],
            ]
        ]);

        $currentModule = $this->getCurrentModule();
        if ($currentModule !== $this->module) {
            return;
        }

        $controller = \yii::$app->controller;
        if ($controller->id !== $this->controller) {
            return;
        }
        $pages = [
            [
                'class' => Home::class
            ],
            [
                'name' => $this->modelName,
                'url' => [
                    $url,
                ],
            ],
        ];
        $action = $controller->action;
        if ($action->id === 'update') {
            $model = $action->adapter->model;
            if ($model->isNewRecord) {
                $name = 'Create ' . lcfirst($this->modelName);
            } else {
                $name = 'Update ' . $model->name;
            }
            $pages[] = [
                'name' => $name,
            ];
        }

        foreach ($pages as $page) {
            $navigation->addPage($page);
        }
    }

    public function getManyModelName() {
        return Inflector::pluralize($this->modelName);
    }

    /**
     * @return string
     */
    protected function getCurrentModule()
    {
        $currentModule = \Yii::$app->controller->module->id;
        return $currentModule;
    }
}