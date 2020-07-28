<?php
/**
 * @author Mamaev Yuriy (eXeCUT)
 * @link https://github.com/execut
 * @copyright Copyright (c) 2020 Mamaev Yuriy (eXeCUT)
 * @license http://www.apache.org/licenses/LICENSE-2.0
 */
namespace execut\crud\bootstrap;

use execut\yii\Bootstrap;

/**
 * Class Backend
 * @package execut\crud
 */
class Backend extends Bootstrap
{
    public $navigation = null;
    public $user = null;
    public $adminRole = null;
    public $moduleId = null;
    public $module = null;
    protected $_defaultDepends = [];

    /**
     * @var Bootstrapper
     */
    protected $bootstrapper = null;

    /**
     * @param Bootstrapper $bootstrapper
     */
    public function setBootstrapper(Bootstrapper $bootstrapper): void
    {
        $this->bootstrapper = $bootstrapper;
    }

    /**
     * @return Bootstrapper
     */
    public function getBootstrapper(): Bootstrapper
    {
        return $this->bootstrapper;
    }

    public function getNavigation()
    {
        $application = \yii::$app;
        if ($application->has('navigation')) {
            return $application->get('navigation');
        }

        return $this->navigation;
    }

    /**
     * @return \yii\web\User
     * @throws \yii\base\InvalidConfigException
     */
    public function getUser()
    {
        if ($this->user === null) {
            $application = \yii::$app;
            if ($application->has('user')) {
                return $application->get('user');
            }
        }

        return $this->user;
    }

    public function getAdminRole()
    {
        if ($this->adminRole === null) {
            return $this->getModule()->getAdminRole();
        }

        return $this->adminRole;
    }

    public function getModuleId()
    {
        return $this->moduleId;
    }

    public function getModule(): Module
    {
        if ($this->module === null) {
            return \yii::$app->getModule($this->getModuleId());
        }

        return $this->module;
    }

    public function bootstrap($app)
    {
        parent::bootstrap($app);
        $bootstrapper = $this->getBootstrapper();
        if ($this->isUserCan()) {
            $bootstrapper->bootstrapForAdmin($this->getNavigation());
        }
    }

    /**
     * @return bool
     * @throws \yii\base\InvalidConfigException
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
