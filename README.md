# yii2-crud
It's package for simple creating CRUD for model between configuring navigation and controller actions in two steps. 

## Installation

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

### Install

Either run

```
$ php composer.phar require execut/yii2-crud "dev-master"
```

or add

```
"execut/yii2-crud": "dev-master"
```

to the ```require``` section of your `composer.json` file.

## Usage

If you need to do a CRUD for your model, you need to take a few steps:
### 1. Create a controller and model
```php
<?php
namespace execut\users\controllers;

use execut\crud\params\Crud;
use execut\users\models\User;
use yii\web\Controller;

class UsersController extends Controller
{
}
```
```php
<?php
namespace execut\users\models;
use yii\db\ActiveRecord;

class User extends ActiveRecord
{
    const MODEL_NAME = '{n,plural,=0{Users} =1{User} other{Users}}';
}
```
### 2. Add actions function inside controller with the helper execut\crud\params\Crud:
```php
    public function actions()
    {
        $crud = new Crud([
            'modelClass' => \execut\users\models\User::class,
            'title' => 'Pages',
        ]);
        return $crud->actions();
    }
```

### 3. Add CRUD configure methods inside model:

Getter for data provider of gridview:
```php
    public function search() {
        return new \yii\data\ActiveDataProvider([
            'query' => self::find(),
        ]);
    }
```

Config for Kartik GridView columns, see [reference](https://github.com/kartik-v/yii2-grid):
```php
    public function getGridColumns() {
        return [
            'id' => [
                'attribute' => 'id',
            ],
            'username' => [
                'attribute' => 'username',
            ],
        ];
    }
```

Config for DetailView form fields, see [reference](http://demos.krajee.com/detail-view):
```php
    public function getFormFields() {
        return [
            'id' => [
                'type' => \kartik\detail\DetailView::INPUT_TEXT,
                'displayOnly' => true,
                'attribute' => 'id',
            ],
            'username' => [
                'type' => \kartik\detail\DetailView::INPUT_TEXT,
                'attribute' => 'username',
            ],
        ];
    }
```

Method for getting string name of founded model:
```php
    public function __toString() {
        return '#' . $this->id;
    }
}
```

All 3 methods you can simplify and replace with one using an additional helper component [execut/yii2-crud-fields](https://github.com/execut/yii2-crud-fields):

```php
<?php
namespace execut\users\models;


use execut\crudFields\Behavior;
use execut\crudFields\BehaviorStub;
use execut\crudFields\fields\Id;
use yii\db\ActiveRecord;
class User extends ActiveRecord
{
    use BehaviorStub;
    const MODEL_NAME = '{n,plural,=0{Users} =1{User} other{Users}}';
    public function behaviors()
    {
        return [
            'fields' => [
                'class' => Behavior::class,
                'fields' => [
                    'id' => [
                        'class' => Id::class,
                    ],
                    'username' => [
                        'attribute' => 'username'
                    ],
                ],
            ],
        ];
    }

    public function __toString() {
        return '#' . $this->id;
    }
}
```

## Navigation

For your CRUD you may use configurator of [execut/yii2-navigation](https://github.com/execut/yii2-navigation) component.
Details of installation and configuration of execut\yii2-navigation can be found in it [documentation](https://github.com/execut/yii2-navigation).

For adding crud items in navigation you may use Configurator of execut\yii2-navigation:
```php
    /**
     * @var Component $navigation
     */
    $navigation = $app->navigation;
    $navigation->addConfigurator([
        'class' => \execut\crud\navigation\Configurator::class,
        'module' => 'users',
        'moduleName' => 'Users',
        'modelName' => \execut\users\models\User::MODEL_NAME,
        'controller' => $model . 's',
    ]);
```
## I18n
\execut\users\models\User::MODEL_NAME - it is constant for work i18n translator in navigation configurator: 
```php
<?php
namespace execut\users\models;
use yii\db\ActiveRecord;

class User extends ActiveRecord
{
    const MODEL_NAME = '{n,plural,=0{Users} =1{User} other{Users}}';
}
```

For configuring i18n in navigation you may configure it in application bootstrap:
```php
$app->i18n->translations['execut/users'] = [
    'class' => 'yii\i18n\PhpMessageSource',
    'sourceLanguage' => 'en-US',
    'basePath' => '@vendor/execut/users/messages',
    'fileMap' => [
        'execut/user' => 'user.php',
    ],
];
```

Here messages required for CRUD navigation translations in file vendor/execut/users/messages/ru/user.php:
```php
<?php
return [
    'Users' => 'Пользователи',
    \execut\users\models\User::MODEL_NAME => '{n,plural,=0{Пользователей} =1{Пользователь} other{Пользователи}}',
];
```