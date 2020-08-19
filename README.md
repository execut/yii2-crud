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

Let's say you need to make a CRUD for the model [execut\books\models\Book](https://github.com/execut/yii2-books/blob/master/models/Book.php)
To do this, just add the following lines to controller: 
```php
namespace execut\books\controllers;

use execut\books\models\Book;
use execut\crud\params\Crud;
use yii\web\Controller;
class BooksController extends Controller
{
    public function actions()
    {
        $crud = new Crud([
            'modelClass' => Book::class,
            'modelName' => Book::MODEL_NAME,
        ]);
        return $crud->actions();
    }
}
```
As a result, a full-fledged CRUD will appear for this model:
![Books CRUD list](https://raw.githubusercontent.com/execut/yii2-crud/master/docs/guide/i/books-list.jpg)
![Books CRUD form](https://raw.githubusercontent.com/execut/yii2-crud/master/docs/guide/i/books-form.jpg)

For more details please refer to the documentation [docs/guide/README.md](https://github.com/execut/yii2-crud-fields/blob/master/docs/guide/README.md).
Для более подробной информации обращайтесь к документации [docs/guide-ru/README.md](https://github.com/execut/yii2-crud-fields/blob/master/docs/guide-ru/README.md).