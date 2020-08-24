В качестве примера рассмотрим процесс создания CRUD-а книг и их авторов.
Такой CRUD для сравнения уже реализован в нативном виде в данном пакете [execut/yii2-crud-native](https://github.com/execut/yii2-crud-native), а с использованием Yii2 CRUD в этом [execut/yii2-books](https://github.com/execut/yii2-books).
Все примеры в дальнейшем будут ссылаться на них.

Список функционала реализованного в нативном CRUD-е приведён на [этой странице](https://github.com/execut/yii2-books-native/blob/master/docs/guide-ru/implemented-functionality.md).
С помощью Yii2 CRUD мы можем автоматизировать создание следующих блоков из этого списка:
* Навигация CRUD
* Контроллер CRUD
    * Вывод списка
        * Действие для вывода списка записей
           * Загрузка параметров фильтрации
           * Задание в модели правил валидации для сценария search путём объявления ```Book::rules()```
           * Валидация модели по сценарию grid
           * Вывод представления 
        * Представление для вывода списка записей
           * Вывод кнопки добавления новой записи
           * Вывод списка записей через виджет [kartik-v/yii2-dynagrid](https://github.com/kartik-v/yii2-dynagrid)
    * Создание или редактирование записей
        * Действие для создания или редактирования записей
            * Если это не новая запись, то поиск активной модели
            * Если новая, то создание
            * Задание сценария edit
            * Загрузка данных модели из запроса
            * Валидация данных модели
            * Сохранение модели
            * Вывод ошибок
        * Представление для вывода формы редактирования активной записи
            * Вывод формы редактирования
    * Действие для удаления записей

За счёт этого предлагаемый инструмент в разы сократит время разработки CRUD-ов без копи-паста\генераторов и тем самым
повысит её эффективность.

## Подготавливаем проект
Установите пример CRUD-а из этого пакета [execut/yii2-books](https://github.com/execut/yii2-books). С его помощью мы
увидим все преимущества использования Yii2 CRUD и не будем тратить время на такие подробности как миграции базы данных и
разворачивания инфраструктуры модуля книг. Далее я покажу шаги, которые необходимо повторить при создании нового CRUD-а
с оглядкой на указанный пример.

### 1. Создаём модуль
Необходимо создать модуль книг для наших будущих CRUD-ов [execut\books\Module](https://github.com/execut/yii2-books/blob/master/Module.php).
Чтобы Yii2 CRUD знал какая роль является администратором, в нём нужно реализовать интерфейс [execut\crud\bootstrap\Module](https://github.com/execut/yii2-crud/blob/master/bootstrap/Module.php):
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

### 2. Создаём модели
Необходимо создать 3 модели для управления книгами, авторами и связями между ними. В моделях для CRUD-ов, для книг и
авторов необходимо объявить 6 методов для успешной работы CRUD-а:
```php
    // Название таблицы в БД
    public static function tableName();
    // DataProvider для вывода списка
    public function search();
    // Правила валидации атрибутов
    public function rules();
    // Настройки колонок GridView
    public function getGridColumns();
    // Настройки атрибутов DetailView для формы редактирования
    public function getFormFields();
    // Подписи атрибутов
    public function attributeLabels();
```
Можно взять готовые версии для этих моделей в нативной версии. Прежде чем использовать, замените пространство имён
booksNative на books:
* [execut\booksNative\models\Book](https://github.com/execut/yii2-books-native/blob/master/src/models/Book.php)
* [execut\booksNative\models\Author](https://github.com/execut/yii2-books-native/blob/master/src/models/Author.php)
* [execut\booksNative\models\AuthorVsBook](https://github.com/execut/yii2-books-native/blob/master/src/models/AuthorVsBook.php)

Или тоже готовые модели, но в более компактном и функциональном варианте за счёт использования
[yii2-crud-fields](https://github.com/execut/yii2-crud-fields):
* [execut\books\models\Book](https://github.com/execut/yii2-books/blob/master/src/models/Book.php)
* [execut\books\models\Author](https://github.com/execut/yii2-books/blob/master/src/models/Author.php)
* [execut\books\models\AuthorVsBook](https://github.com/execut/yii2-books/blob/master/src/models/AuthorVsBook.php)

### 3. Создаём контроллер
Создадим контроллер [execut\books\controllers\BooksController](https://github.com/execut/yii2-books/blob/master/controllers/BooksController.php) с одним небольшим методом actions(), который объявляет все необходимые действия автоматически:

```php
namespace execut\books\controllers;

use execut\books\models\Book;
use execut\crud\params\Crud;
use yii\filters\AccessControl;
use yii\web\Controller;
class BooksController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => [$this->module->getAdminRole()],
                    ],
                ],
            ],
        ];
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

В behaviors() мы видим, что добавляется правило, которое запрещает доступ к контроллеру всем, кроме роли, заданной в модуле.

В actions() используется класс [execut\crud\params\Crud](https://github.com/execut/yii2-crud/blob/master/params/Crud.php) для автоматического формирования всех
действий и подписей для модели modelClass с названием modelName.

Если сравнивать с нативным способом реализации данного контроллера [execut\booksNative\controllers\BooksController](https://github.com/execut/yii2-books-native/blob/master/src/controllers/BooksController.php),
то мы видим, что новый [execut\books\controllers\BooksController](https://github.com/execut/yii2-books/blob/master/controllers/BooksController.php) имеет следующие преимущества:
1. Не нужно объявлять дейcтсвия index, update и delete, они сформировались автоматически
1. Не нужно писать никакого функционала действий по работе с моделью (index, update, delete), всё инкапсулировано в действиях компонента [execut/yii2-actions](https://github.com/execut/yii2-actions)
1. Нет представлений index и update, все обявлено автоматически и инкапсулировано с помощью [execut/yii2-actions](https://github.com/execut/yii2-actions)
1. Нет необходимости формировать по всем интерфейсам подписи-названия модели, они рассчитываются автоматически

Тоже самое повторяем и с моделью авторов в контроллере [execut\books\controllers\AuthorsController](https://github.com/execut/yii2-books/blob/master/controllers/AuthorsController.php)

### 4. Настраиваем навигацию
Чтобы Yii2 CRUD автоматически настраивал вашу навигацию, можно использовать [execut/yii2-navigation](https://github.com/execut/yii2-navigation).
Обратитесь к его документации, чтобы понять как yii2-navigation подключить к вашему проекту.

Пишем предзагрузчик навигации для наших моделей [execut\books\bootstrap\backend\Bootstrapper](https://github.com/execut/yii2-books/blob/master/bootstrap/backend/Bootstrapper.php),
реализуя в нём интерфейс [\execut\crud\bootstrap\Bootstrapper](https://github.com/execut/yii2-crud/blob/master/bootstrap/Bootstrapper.php):
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

И подключаем его в предзагрузчике модуля [execut\books\bootstrap\Backend](https://github.com/execut/yii2-books/blob/master/bootstrap/Backend.php):
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

После этого в админке должно появиться меню с разделами:

![Меню раздела](https://raw.githubusercontent.com/execut/yii2-crud/master/docs/guide-ru/i/books-menu.jpg)

И сами разделы CRUD:

![Список книг](https://raw.githubusercontent.com/execut/yii2-crud/master/docs/guide-ru/i/books-list.jpg)
![Форма книг](https://raw.githubusercontent.com/execut/yii2-crud/master/docs/guide-ru/i/books-form.jpg)
![Список авторов](https://raw.githubusercontent.com/execut/yii2-crud/master/docs/guide-ru/i/authors-list.jpg)
![Форма авторов](https://raw.githubusercontent.com/execut/yii2-crud/master/docs/guide-ru/i/authors-form.jpg)