<?php
/**
 * @author Mamaev Yuriy (eXeCUT)
 * @link https://github.com/execut
 * @copyright Copyright (c) 2020 Mamaev Yuriy (eXeCUT)
 * @license http://www.apache.org/licenses/LICENSE-2.0
 */
namespace execut\crud\params;

use execut\actions\Action;
use execut\actions\action\adapter\Delete;
use execut\actions\action\adapter\Edit;
use execut\actions\action\adapter\EditWithRelations;
use execut\actions\action\adapter\GridView;
use execut\crud\Translator;
use execut\crudFields\fields\HasManySelect2;
use kartik\detail\DetailView;
use yii\base\BaseObject;
use yii\helpers\ArrayHelper;
use yii\helpers\UnsetArrayValue;

/**
 * CRUD actions config factory
 * @package execut\crud
 */
class Crud extends BaseObject
{
    /**
     * @var string Target model class for CRUD
     */
    public $modelClass = null;
    /**
     * @var string Module id string
     */
    public $module = null;
    /**
     * @var string Module name label for translations
     */
    public $moduleName = null;
    /**
     * @var string Model name label for translations
     */
    public $modelName = null;
    /**
     * @var array Relations list for rendering relations lists inside edit form
     * @deprecated
     * @see HasManySelect2
     */
    public $relations = [];
    /**
     * @var string Current user role string
     */
    public $role = null;
    /**
     * @var array Configuration for roles
     */
    public $rolesConfig = [];

    /**
     * Returns default roles config
     * @return array[]
     */
    public function getDefaultRolesConfig()
    {
        return [
            'user' => [
                'index' => [
                    'adapter' => [
                        'view' => [
                            'title' => $this->getTranslator()->getModelLabel(1),
                            'widget' => [
                                'gridOptions' => [
                                    'toolbar' => [
                                        'massEdit' => new UnsetArrayValue(),
                                        'massVisible' => new UnsetArrayValue(),
                                        'dynaParams' => new UnsetArrayValue(),
                                        'toggleData' => new UnsetArrayValue(),
                                        'export' => new UnsetArrayValue(),
                                        'refresh' => new UnsetArrayValue(),
                                    ],
                                    'layout' => '{alertBlock}<div class="dyna-grid-footer">{summary}{pager}<div class="dyna-grid-toolbar">{toolbar}</div></div>{items}',
                                    'filterPosition' => '123',
                                ],
                            ],
                        ],
                    ],
                ],
                'update' => [
                    'adapter' => [
                        'view' => [
                            'buttonsTemplate' => '{save}&nbsp;&nbsp;{cancel}',
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * Returns default current role CRUD actions configuration
     * @return array
     */
    public function getDefaultRoleConfig()
    {
        $config = $this->getDefaultRolesConfig();
        if (isset($config[$this->role])) {
            return $config[$this->role];
        }

        return [];
    }

    /**
     * Returns current role CRUD actions configuration
     * @return array
     */
    public function getRoleConfig()
    {
        $config = $this->rolesConfig;
        if (isset($config[$this->role])) {
            return $config[$this->role];
        }

        return [];
    }

    /**
     * Returns actions configuration of CRUD controller for model
     * @param array $mergedActions
     * @return array
     */
    public function actions($mergedActions = [])
    {
        if (empty($this->relations)) {
            $updateAdapterParams = $this->getUpdateAdapterParams();
        } else {
            $updateAdapterParams = $this->getUpdateAdapterParamsWithRelations();
        }

        $listAdapterParams = $this->getListAdapterParams();

        $result = ArrayHelper::merge(ArrayHelper::merge(ArrayHelper::merge([
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
        ], $this->getDefaultRoleConfig()), $this->getRoleConfig()), $mergedActions);

        return $result;
    }

    /**
     * Create CRUD translator object
     * @param null $relation
     * @return Translator
     */
    public function getTranslator($relation = null)
    {
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
     * Returns update action config
     * @return array
     */
    protected function getUpdateAdapterParams(): array
    {
        $updateActionParams = [
            'class' => Edit::class,
            'mode' => DetailView::MODE_EDIT,
            'modelClass' => $this->modelClass,
            'scenario' => 'form',
            'editFormLabel' => function () {
                return $this->getTranslator()->getUpdateLabel();
            },
            'createFormLabel' => $this->getTranslator()->getCreateLabel(),
        ];

        return $updateActionParams;
    }

    /**
     * @deprecated
     * @return array
     */
    protected function getUpdateAdapterParamsWithRelations(): array
    {
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
     * Returns list action configuration
     * @return array
     */
    protected function getListAdapterParams($relation = null): array
    {
        $listAdapterParams = [
            'class' => GridView::class,
            'scenario' => 'grid',
        ];

        if ($relation === null) {
            $listAdapterParams['model'] = [
                'class' => $this->modelClass,
            ];
        }

        return $listAdapterParams;
    }
}
