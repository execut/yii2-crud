# Yii2 CRUD
It's package for simple creating CRUD from configuring navigation to required controller actions in 3 steps
without copy-paste or CRUD generators. 

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

Add bootstrap to your application config:
```php
return [
    'bootstrap' => [
        'yii2-crud' => [
            'class' => \execut\crud\Bootstrap::class,
        ]
    ]
];
```

## Usage

[Read wiki](https://github.com/execut/yii2-crud/wiki)
