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

If you need to do a CRUD for your model, you need to take a two steps:
1. Create a controller
2. Add actions function with the helper execut\crud\params\Crud:

```php
    public function actions()
    {
        $crud = new Crud([
            'modelClass' => Page::class,
            'title' => 'Pages',
        ]);
        return $crud->actions();
    }
```

For adding crud items in navigation you may use Configurator of execut\yii2-navigation:
```php

```
Details of installation and configuration of execut\yii2-navigation can be found in it [documentation](https://github.com/execut/yii2-navigation).