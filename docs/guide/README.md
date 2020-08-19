As an example, consider the process of creating CRUD for books and their authors.
This CRUD for comparison is already build into this package on native Yii2 [execut/yii2-crud-native](https://github.com/execut/yii2-crud-native), and on Yii2 CRUD in this [execut/yii2-books](https://github.com/execut/yii2-books).
All examples will further refer to on them.

The list of functionality implemented in the CRUD is give on [this page](https://github.com/execut/yii2-books-native/blob/master/docs/guide/implemented-functionality.md).
With Yii2 CRUD, we can automate the creation of the following blocks from this list:
* CRUD navigation
* CRUD controller
    * List output
        * Action to display the list of records
           * Loading filtering parameters
           * Setting validation rules for the search script in the model by declaring ``` Book::rules() ```
           * Model validation by grid script
           * Output view
        * View to display the list of records
           * Output of the add new entry button
           * Displaying the list of records via the [kartik-v/yii2-dynagrid](https://github.com/kartik-v/yii2-dynagrid) widget
    * Create or edit records
        * Action to create or edit records
            * If this is not a new entry, then search for the active model
            * If new, then creation
            * Assign script edit
            * Load model data from query
            * Model data validation
            * Save the model
            * Error output
        * View to display the edit form of  active record
            * Output of edit form
    * Action to delete records

Due to this, the proposed tool will significantly reduce the development time of CRUDs without copy-paste\generators and thereby increase your efficiency.

## We prepare the project
Install the CRUD example from this package [execut/yii2-books](https://github.com/execut/yii2-books).
With its help, we will see all the benefits of using Yii2 CRUD and will not waste time on such details as database migrations and deploying the books module infrastructure.
Next, I will show the steps that need to be repeated when creating a new CRUD, taking into account the specified example.

### 1. Module creation
We need to create a book module for our future CRUDs [execut\books\Module](https://github.com/execut/yii2-books/blob/master/Module.php).
In order for Yii2 CRUD to know which role is the administrator, needs to implement the [execut\crud\bootstrap\Module](https://github.com/execut/yii2-crud/blob/master/bootstrap/Module.php) interface:
```php
namespace execut\books;
class Module extends \yii\base\Module implements \execut\crud\bootstrap\Module
{
    public $adminRole = '@';
    public function getAdminRole() {
        return $this->adminRole;
    }
}
```

### 2. Models creation
You need to create 3 models to manage books, authors and relationships between them.
In models for CRUDs, for books and authors, you need to declare 6 methods for the successful operation of CRUDs:
```php
    // Name of the table in the database
    public static function tableName();
    // DataProvider to display the list
    public function search();
    // Attribute validation rules
    public function rules();
    // GridView column settings
    public function getGridColumns();
    // DetailView attribute settings for edit form
    public function getFormFields();
    // Attribute signatures
    public function attributeLabels();
```
You can take ready-made versions for these models in the native version. Change the booksNative namespace to books before using:
* [execut\booksNative\models\Book](https://github.com/execut/yii2-books-native/blob/master/models/Book.php)
* [execut\booksNative\models\Author](https://github.com/execut/yii2-books-native/blob/master/models/Author.php)
* [execut\booksNative\models\AuthorVsBook](https://github.com/execut/yii2-books-native/blob/master/models/AuthorVsBook.php)

Or, also ready-made models, but in a more compact and functional version by using [yii2-crud-fields](https://github.com/execut/yii2-crud-fields):
* [execut\books\models\Book](https://github.com/execut/yii2-books/blob/master/models/Book.php)
* [execut\books\models\Author](https://github.com/execut/yii2-books/blob/master/models/Author.php)
* [execut\books\models\AuthorVsBook](https://github.com/execut/yii2-books/blob/master/models/AuthorVsBook.php)

### 3. Controller creation
Let's create a controller [execut\books\controllers\BooksController](https://github.com/execut/yii2-books/blob/master/controllers/BooksController.php) with one small actions() method that declares all the necessary actions automatically:

```php
namespace execut\books\controllers;

use execut\books\models\Book;
use execut\crud\params\Crud;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
class BooksController extends Controller
{
    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(), [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => [$this->module->getAdminRole()],
                    ],
                ],
            ],
        ]);
    }

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

In behaviors(), we see that a rule is being added that prohibits access to the controller to everyone except the role specified in the module.

Actions() uses the [execut\crud\params\Crud](https://github.com/execut/yii2-crud/blob/master/params/Crud.php) class to automatically generate all actions and signatures for the modelClass named modelName.

If we compare with the native implementation of this controller [execut\booksNative\controllers\BooksController](https://github.com/execut/yii2-books-native/blob/master/controllers/BooksController.php), we see that the new [execut\books\controllers\BooksController](https://github.com/execut/yii2-books/blob/master/controllers/BooksController.php) has the following benefits:
1. There is no need to declare the index, update and delete actions, they are generated automatically
1. There is no need to write any functionality of actions to work with the model (index, update, delete), everything is encapsulated in the actions of the component [execut/yii2-actions](https://github.com/execut/yii2-actions)
1. No index and update views, everything is automatically declared and encapsulated by [execut/yii2-actions](https://github.com/execut/yii2-actions)
1. There is no need to generate model labels of attributes for all interfaces, they are calculated automatically

We repeat the same with the author's model in the controller [execut\books\controllers\AuthorsController](https://github.com/execut/yii2-books/blob/master/controllers/AuthorsController.php)

### 4. Navigation setup
To have Yii2 CRUD automatically customize your navigation, you can use [execut/yii2-navigation](https://github.com/execut/yii2-navigation).
Refer to its documentation to understand how yii2-navigation can be connected to your project.

We write a navigation preloader for our models [execut\books\bootstrap\backend\Bootstrapper](https://github.com/execut/yii2-books/blob/master/bootstrap/backend/Bootstrapper.php), implementing the interface [ \ execut \ crud \ bootstrap \ Bootstrapper] (https://github.com/execut/yii2-crud/blob/master/bootstrap/Bootstrapper.php):
```php
namespace execut\books\bootstrap\backend;
use execut\crud\navigation\Configurator;
use execut\navigation\Component;
use execut\books\models\Book;
use execut\books\models\Author;
//...
class Bootstrapper implements \execut\crud\bootstrap\Bootstrapper
{
    public function bootstrapForAdmin(Component $navigation)
    {
        //...
        $navigation->addConfigurator([
            'class' => Configurator::class,
            'module' => 'books',
            'moduleName' => 'Books CRUD examples',
            'modelName' => Book::MODEL_NAME,
            'controller' => 'books',
        ]);

        $navigation->addConfigurator([
            'class' => Configurator::class,
            'module' => 'books',
            'moduleName' => 'СRUD fields examples',
            'modelName' => Author::MODEL_NAME,
            'controller' => 'authors',
        ]);
    }
}
```

And connect it in the preloader of the [execut\books\bootstrap\Backend](https://github.com/execut/yii2-books/blob/master/bootstrap/Backend.php) module:
```php
namespace execut\books\bootstrap;
use execut\crud\bootstrap\Bootstrapper;
class Backend extends \execut\crud\bootstrap\Backend
{
    /**
     * @return Bootstrapper
     */
    public function getBootstrapper(): Bootstrapper
    {
        if ($this->bootstrapper === null) {
            $this->bootstrapper = new \execut\books\bootstrap\backend\Bootstrapper;
        }

        return $this->bootstrapper;
    }
}
```

After that, a menu with sections should appear in the admin panel:

![Section menu](https://raw.githubusercontent.com/execut/yii2-crud/master/docs/guide/i/books-menu.jpg)

And the CRUD sections themselves:

![List of books](https://raw.githubusercontent.com/execut/yii2-crud/master/docs/guide/i/books-list.jpg)
![Books form](https://raw.githubusercontent.com/execut/yii2-crud/master/docs/guide/i/books-form.jpg)
![List of books](https://raw.githubusercontent.com/execut/yii2-crud/master/docs/guide/i/authors-list.jpg)
![Authors form](https://raw.githubusercontent.com/execut/yii2-crud/master/docs/guide/i/authors-form.jpg)