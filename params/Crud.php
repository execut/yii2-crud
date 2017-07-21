<?php
/**
 */

namespace execut\crud\params;


use execut\actions\Action;
use execut\actions\action\adapter\Delete;
use execut\actions\action\adapter\Edit;
use execut\actions\action\adapter\EditWithRelations;
use execut\actions\action\adapter\GridView;
use execut\crud\Translator;
use execut\crudFields\fields\Field;
use kartik\detail\DetailView;
use yii\base\Object;
use yii\helpers\ArrayHelper;

class Crud extends Object
{
    public $modelClass = null;
    public $module = null;
    public $moduleName = null;
    public $modelName = null;
    public $relations = [];
    public function actions()
    {
        if (empty($this->relations)) {
            $updateAdapterParams = $this->getUpdateAdapterParams();
        } else {
            $updateAdapterParams = $this->getUpdateAdapterParamsWithRelations();
        }

        $listAdapterParams = $this->getListAdapterParams();

        return [
            'index' => [
                'class' => Action::class,
                'adapter' => $listAdapterParams,
            ],
            'update' => [
                'class' => Action::class,
                'adapter' => $updateAdapterParams,
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

    public function getTranslator($relation = null) {
        $translatorParams = [
            'module' => $this->module,
            'modelName' => $this->modelName,
            'moduleName' => $this->moduleName,
        ];

        if (!empty($this->relations[$relation])) {
            $translatorParams = ArrayHelper::merge($translatorParams, $this->relations[$relation]);
        }

        return new Translator($translatorParams);
    }

    /**
     * @return array
     */
    protected function getUpdateAdapterParams(): array
    {
        $updateActionParams = [
            'class' => Edit::class,
            'mode' => DetailView::MODE_EDIT,
            'modelClass' => $this->modelClass,
            'scenario' => Field::SCENARIO_FORM,
            'editFormLabel' => function () {
                return $this->getTranslator()->getUpdateLabel();
            },
            'createFormLabel' => $this->getTranslator()->getCreateLabel(),
        ];

        return $updateActionParams;
    }

    protected function getUpdateAdapterParamsWithRelations():array {
        $relations = $this->relations;
        $resultRelations = [];
        foreach ($relations as $relation => $translatorParams) {
            $resultRelations[$relation] = $this->getListAdapterParams($relation);
        }

        return [
            'class' => EditWithRelations::class,
            'editAdapterConfig' => $this->getUpdateAdapterParams(),
            'relations' => $resultRelations,
        ];
    }

    /**
     * @return array
     */
    protected function getListAdapterParams($relation = null): array
    {
        $listAdapterParams = [
            'class' => GridView::class,
            'scenario' => Field::SCENARIO_GRID,
            'view' => [
                'title' => $this->getTranslator($relation)->getManyModelName(0),
            ],
        ];

        if ($relation === null) {
            $listAdapterParams['model'] = [
                'class' => $this->modelClass,
            ];
        }

        return $listAdapterParams;
    }
}