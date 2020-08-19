<?php
/**
 * @author Mamaev Yuriy (eXeCUT)
 * @link https://github.com/execut
 * @copyright Copyright (c) 2020 Mamaev Yuriy (eXeCUT)
 * @license http://www.apache.org/licenses/LICENSE-2.0
 */
namespace execut\crud;

use execut\actions\Action;
use execut\actions\action\adapter\Edit;
use execut\actions\action\adapter\EditWithRelations;
use yii\base\BaseObject;

/**
 * Class Translator
 * @package execut\crud
 */
class Translator extends BaseObject
{
    /**
     * @var string Module id string for generation i18n translate category
     */
    public $module = null;
    /**
     * @var string Model name label for translation
     */
    public $modelName = null;
    /**
     * @var string Module name label for translation
     */
    public $moduleName = null;
    /**
     * Translate message via module translate category
     * @param string $message Message string for translation
     * @param array $params Params list for translation
     * @return string
     */
    protected function moduleTranslate($message, $params = ['n' => 1])
    {
        if ($this->module === null) {
            return $message;
        }

        $category = 'execut/' . $this->module;
        $result = $this->translate($message, $params, $category);

        return $result;
    }

    /**
     * Translate message of CRUD module
     * @param $moduleName
     * @return string
     */
    protected function crudTranslate($message, $params = []): string
    {
        $category = 'execut/crud/';
        $result = $this->translate($message, $params, $category);

        return $result;
    }

    /**
     * lcfirst analog for md
     * @param string $string Target string
     * @param string $encoding Target encoding
     * @return string
     */
    protected function lcfirst($string, $encoding = 'UTF-8')
    {
        $first = mb_convert_case(mb_substr($string, 0, 1, $encoding), MB_CASE_LOWER, $encoding);

        return $first . mb_substr($string, 1, null, $encoding);
    }

    /**
     * Returns translated the plural of a message
     * @param int $n
     * @return string
     */
    public function getManyModelName($n = 22)
    {
        return $this->moduleTranslate($this->modelName, ['n' => $n]);
    }

    /**
     * Returns translated model create label
     * @return string
     */
    public function getCreateLabel()
    {
        return $this->crudTranslate('New') . ' ' . $this->lcfirst($this->getModelLabel(1));
    }

    /**
     * Returns translated model update label
     * @return string
     */
    public function getUpdateLabel()
    {
        $title = $this->getModelTitle();
        if ($title) {
            $title = $this->moduleTranslate($title);
        }

        return $this->getModelLabel(1) . ' ' . $title;
    }

    /**
     * Returns translated model title
     * @return string
     */
    public function getModelTitle()
    {
        $controller = \yii::$app->controller;
        $action = $controller->action;
        if ($action instanceof Action && ($action->adapter instanceof Edit || $action->adapter instanceof EditWithRelations)) {
            $model = $action->adapter->getModel();

            return (string) $model;
        }

        return null;
    }

    /**
     * Returns translated model label
     * @param integer $n Models count, parameter n for i18n
     * @return string
     */
    public function getModelLabel($n)
    {
        return $this->moduleTranslate($this->modelName, ['n' => $n]);
    }

    /**
     * Returns translated module label
     * @return string
     */
    public function getModuleLabel()
    {
        return $this->moduleTranslate($this->moduleName, ['n' => 31]);
    }

    /**
     * Translate message via i18n
     * @param string $message Message string
     * @param array $params Params array
     * @param string $category Category
     * @return string
     */
    protected function translate($message, $params, $category)
    {
        $result = \yii::t($category, $message, $params);

        return $result;
    }
}
