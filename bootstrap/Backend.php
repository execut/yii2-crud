<?php


namespace execut\crud\bootstrap;


use execut\yii\Bootstrap;

class Backend extends Bootstrap
{
    public $navigation = null;
    public $user = null;
    public $adminRole = null;
    public $moduleId = null;
    public $module = null;
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

    public function getNavigation() {
        $application = \yii::$app;
        if ($application->has('navigation')) {
            return $application->get('navigation');
        }

        return $this->navigation;
    }

    public function getUser() {
        if ($this->user === null) {
            $application = \yii::$app;
            if ($application->has('user')) {
                return $application->get('user');
            }
        }

        return $this->user;
    }

    public function getAdminRole() {
        if ($this->adminRole === null) {
            return $this->getModule()->getAdminRole();
        }

        return $this->adminRole;
    }

    public function getModuleId() {
        return $this->moduleId;
    }

    public function getModule():Module {
        if ($this->module === null) {
            return \yii::$app->getModule($this->getModuleId());
        }

        return $this->module;
    }

    public function bootstrap($app)
    {
        parent::bootstrap($app);
        $bootstrapper = $this->getBootstrapper();
        if ($this->getUser()->can($this->getAdminRole())) {
            $bootstrapper->bootstrapForAdmin($this->getNavigation());
        }
    }
}