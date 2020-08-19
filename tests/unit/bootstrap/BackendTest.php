<?php
/**
 * @author Mamaev Yuriy (eXeCUT)
 * @link https://github.com/execut
 * @copyright Copyright (c) 2020 Mamaev Yuriy (eXeCUT)
 * @license http://www.apache.org/licenses/LICENSE-2.0
 */
namespace execut\crud\tests\unit\bootstrap;

use Codeception\Test\Unit;
use execut\crud\bootstrap\Backend;
use execut\crud\bootstrap\Bootstrapper;
use execut\crud\bootstrap\Module;
use execut\navigation\Component;
use yii\base\Application;
use yii\web\User;

/**
 * Class BackendTest
 * @package execut\crud\tests
 */
class BackendTest extends Unit
{
    public function testGetNavigationByDefault()
    {
        $navigation = new Component();
        \yii::$app->set('navigation', $navigation);
        $bootstrap = new Backend();
        $this->assertEquals($navigation, $bootstrap->getNavigation());
        \yii::$app->set('navigation', null);
    }

    public function testSetNavigationFromConstructor()
    {
        $navigation = new Component();
        $bootstrap = new Backend([
            'navigation' => $navigation,
        ]);
        $this->assertEquals($navigation, $bootstrap->getNavigation());
    }

    public function testGetUserByDefault()
    {
        $user = new User([
            'identityClass' => BackendTestUser::class
        ]);
        \yii::$app->set('user', $user);
        $bootstrap = new Backend();
        $this->assertEquals($user, $bootstrap->getUser());
        \yii::$app->set('user', null);
    }

    public function testSetUserFromConstructor()
    {
        $user = new User([
            'identityClass' => BackendTestUser::class
        ]);
        $bootstrap = new Backend([
            'user' => $user,
        ]);
        $this->assertEquals($user, $bootstrap->getUser());
    }

    public function testGetModuleIdFromConstructor()
    {
        $moduleId = 'test-module';
        $bootstrap = new Backend([
            'moduleId' => $moduleId
        ]);
        $this->assertEquals($moduleId, $bootstrap->getModuleId());
    }

    public function testGetModuleFromConstructor()
    {
        $moduleId = 'module';
        $module = $this->getMockBuilder(BackendTestModule::class)->setConstructorArgs([$moduleId])->getMock();
        $bootstrap = new Backend([
            'module' => $module
        ]);
        $this->assertEquals($module, $bootstrap->getModule());
    }

    public function testGetModuleByDefault()
    {
        $moduleId = 'module';
        /**
         * @var BackendTestModule $module
         */
        $module = $this->getMockBuilder(BackendTestModule::class)->setConstructorArgs([$moduleId])->getMock();
        \yii::$app->setModule($moduleId, $module);
        $bootstrap = new Backend([
            'moduleId' => $moduleId,
        ]);
        $this->assertEquals($module, $bootstrap->getModule());
        \yii::$app->setModule($moduleId, null);
    }

    public function testGetAdminRoleByDefault()
    {
        $role = 'test';
        $moduleId = 'module';
        $module = $this->getMockBuilder(BackendTestModule::class)->setConstructorArgs([$moduleId])->getMock();
        $module->method('getAdminRole')
            ->willReturn($role);

        /**
         * @var BackendTestModule $module
         */
        \yii::$app->setModule($moduleId, $module);
        $this->assertEquals($module, \yii::$app->getModule($moduleId));
        $bootstrap = new Backend([
            'module' => $module
        ]);
        $this->assertEquals($role, $bootstrap->getAdminRole());
        \yii::$app->setModule($moduleId, null);
    }

    public function testSetAdminRoleFromConstructor()
    {
        $role = 'test';
        $bootstrap = new Backend([
            'adminRole' => $role,
        ]);
        $this->assertEquals($role, $bootstrap->getAdminRole());
    }

    public function testBootstrapViaRequestBeginEvent() {
        $bootstrapper = $this->getMockBuilder(Bootstrapper::class)->getMock();
        $bootstrapper->expects($this->never())
            ->method('bootstrapForAdmin');

        $user = $this->getMockBuilder(User::class)->getMock();
        $user->method('can')
            ->willReturn(true);

        $bootstrap = new Backend([
            'bootstrapper' => $bootstrapper,
            'user' => $user,
            'adminRole' => 'adminRole',
        ]);
        $app = \yii::$app;
        $bootstrap->bootstrap($app);
    }

    public function testBootstrapForSimpleUser()
    {
        $bootstrapper = $this->getMockBuilder(Bootstrapper::class)->getMock();
        $bootstrapper->expects($this->never())
            ->method('bootstrapForAdmin');

        $user = $this->getMockBuilder(User::class)->getMock();
        $user->method('can')
            ->with('adminRole')
            ->willReturn(false);

        $bootstrap = new Backend([
            'bootstrapper' => $bootstrapper,
            'user' => $user,
            'adminRole' => 'adminRole',
        ]);
        $app = \yii::$app;
        $bootstrap->bootstrap($app);
        $app->trigger(Application::EVENT_BEFORE_REQUEST);
    }

    public function testBootstrapForAdmin()
    {
        $bootstrapper = $this->getMockBuilder(Bootstrapper::class)->getMock();
        $navigation = new Component();
        $bootstrapper->expects($this->once())
            ->method('bootstrapForAdmin')
            ->with($navigation);

        $user = $this->getMockBuilder(User::class)->getMock();
        $user->method('can')
            ->with('adminRole')
            ->willReturn(true);

        $bootstrap = new Backend([
            'bootstrapper' => $bootstrapper,
            'user' => $user,
            'navigation' => $navigation,
            'adminRole' => 'adminRole',
        ]);
        $app = \yii::$app;
        $bootstrap->bootstrap($app);
        $app->trigger(Application::EVENT_BEFORE_REQUEST);
    }
}

class BackendTestUser extends User
{
}

class BackendTestModule extends \yii\base\Module implements Module
{
    public function getAdminRole()
    {
    }
}
