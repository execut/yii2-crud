<?php
/**
 */

namespace execut\crud\params;


use execut\actions\Action;
use execut\actions\action\adapter\Delete;
use execut\actions\action\adapter\Edit;
use execut\actions\action\adapter\GridView;
use execut\crudFields\fields\Field;
use yii\base\Object;

class Crud extends Object
{
    public $modelClass = null;
    public $title = null;
    public function actions()
    {
        return [
            'index' => [
                'class' => Action::class,
                'adapter' => [
                    'class' => GridView::class,
                    'model' => [
                        'class' => $this->modelClass,
                        'scenario' => Field::SCENARIO_GRID,
                    ],
                    'view' => [
                        'title' => $this->title,
                    ],
                ],
            ],
            'update' => [
                'class' => Action::class,
                'adapter' => [
                    'class' => Edit::class,
                    'modelClass' => $this->modelClass,
                    'scenario' => Field::SCENARIO_FORM,
                ],
            ],
            'delete' => [
                'class' => Action::class,
                'adapter' => [
                    'class' => Delete::class,
                    'modelClass' => $this->modelClass,
                ],
            ],
        ];
    }
}