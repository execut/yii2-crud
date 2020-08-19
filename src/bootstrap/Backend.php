<?php
/**
 * @author Mamaev Yuriy (eXeCUT)
 * @link https://github.com/execut
 * @copyright Copyright (c) 2020 Mamaev Yuriy (eXeCUT)
 * @license http://www.apache.org/licenses/LICENSE-2.0
 */
namespace execut\crud\bootstrap;

use execut\navigation\Component;
use execut\yii\Bootstrap;
use yii\base\Application;
use yii\base\InvalidConfigException as InvalidConfigException;
use yii\filters\AccessRule;
use yii\web\User;
use \yii\base\Module as ModuleBase;

/**
 * Bootstrap for CRUDs. Initialized CRUD via it bootstrapper
 * @package execut\crud
 */
class Backend extends Bootstrap
{
    /**
     * @var Component Navigation component instance
     */
    public $navigation = null;
    /**
     * @var User User application component instance
     */
    public $user = null;
    /**
     * @var string A role that has access to CRUD
     * @see AccessRule::$roles
     */
    public $adminRole = null;
    /**
     * @var string Module id of CRUD
     */
    public $moduleId = null;
    /**
     * @var ModuleBase Module instance of CRUD
     */
    public $module = null;

    /**
     * @var Bootstrapper Bootstrapper for CRUD
     * @see Bootstrapper
     */
    protected $bootstrapper = null;

    /**
     * Sets bootstrapper instance for CRUD
     * @param Bootstrapper $bootstrapper
     */
    public function setBootstrapper(Bootstrapper $bootstrapper): void
    {
        $this->bootstrapper = $bootstrapper;
    }

    /**
     * Returns bootstrapper
     * @return Bootstrapper
     */
    public function getBootstrapper(): Bootstrapper
    {
        return $this->bootstrapper;
    }

    /**
     * Returns the navigation component. Gets from the application by default
     * @return Component
     * @throws InvalidConfigException
     */
    public function getNavigation()
    {
        $application = \yii::$app;
        if ($application->has('navigation')) {
            /**
             * @var Component $navigation
             */
            $navigation = $application->get('navigation');
            return $navigation;
        }

        return $this->navigation;
    }

    /**
     * Returns the navigation component. Gets from the application by default
     * @return User
     * @throws InvalidConfigException
     */
    public function getUser()
    {
        if ($this->user === null) {
            $application = \yii::$app;
            if ($application->has('user')) {
                /**
                 * @var User $user
                 */
                $user = $application->get('user');
                return $user;
            }
        }

        return $this->user;
    }

    /**
     * Returns role string that has access to CRUD
     * @return string
     * @see AccessRule::$roles
     */
    public function getAdminRole()
    {
        if ($this->adminRole === null) {
            return $this->getModule()->getAdminRole();
        }

        return $this->adminRole;
    }

    /**
     * Gets module id of CRUD
     * @return string
     */
    public function getModuleId()
    {
        return $this->moduleId;
    }

    /**
     * Gets module instance of CRUD
     * @return ModuleBase
     */
    public function getModule(): Module
    {
        if ($this->module === null) {
            $module = \yii::$app->getModule($this->getModuleId());
            return $module;
        }

        return $this->module;
    }

    /**
     * {@inheritDoc}
     */
    public function bootstrap($app)
    {
        parent::bootstrap($app);
        $app->on(Application::EVENT_BEFORE_REQUEST, function () {
            $bootstrapper = $this->getBootstrapper();
            if ($this->isUserCan()) {
                $bootstrapper->bootstrapForAdmin($this->getNavigation());
            }
        });
    }

    /**
     * Checks what user is has access to CRUD
     * @return bool
     * @throws InvalidConfigException
     */
    protected function isUserCan(): bool
    {
        $user = $this->getUser();
        $adminRole = $this->getAdminRole();
        if (!$user) {
            return false;
        }
        if ($adminRole === '@') {
            if (!$user->getIsGuest()) {
                return true;
            } else {
                return false;
            }
        } else {
            return $user->can($adminRole);
        }
    }
}
