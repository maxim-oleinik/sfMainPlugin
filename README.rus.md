sfMainPlugin
============

Набор часто используемых расширений.


Требования
----------

  * PHP 5.3 (namespace)

  * Патч к symfony для автозагрузки классов с namespace.  
    https://github.com/maxim-oleinik/Symfony/commit/282e055db90288017df779b0e5d8b3ad108fa674


Установка
---------

  * Установка как submodule для Git-репозитария:

        cd /my/project/dir
        git submodule add git://github.com:maxim-oleinik/sfMainPlugin.git plugins/sfMainPlugin
        cd plugins/sfMainPlugin
        git submodule update --init

  * Запуск тестов:

        phpunit plugins/sfMainPlugin/test/AllTests.php

  * Подключить тесты плагина в общий набор: test/AllTests.php

        [php]
        require_once __DIR__.'/../plugins/sfMainPlugin/test/AllTests.php';

        class AllTests extends PHPUnit_Framework_TestSuite
        {
            public static function suite()
            {
                $suite = new AllTests('PHPUnit');
                ...
                $suite->addTest(\Test\sfMainPlugin\AllTests::suite());
                return $suite;
            }
        }

  * ProjectConfiguration

        $this->enablePlugins('sfMainPlugin', ...);


Использование
-------------

### Config ###

  * Включено logging_enabled. Для PROD в factories используется sfPhpLogger
  * standard_helpers[sfMainCommonHelper]
  * Дефолтный форматтер - sfWidgetFormSchemaFormatterListExt


### Validator ###

  * **sfMainPlugin\Validator\sfValidatorString**  
    Опции:
      * to_lower - Привести к нижнему регистру
      * ucwords  - Каждое слово с большой буквы
      * trim     - Включен по-умолчанию

  * **sfMainPlugin\Validator\sfValidatorEmail**  
    Использует https://github.com/iamcal/rfc822.git  
    Нет опций.

  * **sfMainPlugin\Validator\sfValidatorSlug**  
    Допускает только строки [a-z0-9\-]  
    Нет опций.

  * **sfMainPlugin\Validator\sfValidatorArray**  
    Разбивает строки в массив.  
    Опции:
      * delimiter - Регулярка, разделитель строк, по-умолчанию: '\n+|\r+'


### sfWidgetFormSchemaFormatterListExt ###

Кастомный форматер для форм (List). Включен по-умолчанию.

  * Помечает "label" классом "required", если передать ему sfValidatorSchema
  * Создает имя label по аналогии с ID. Например: "form_field: user_email".
  * В LI добавляет уникальный класс поля
  * Меняет местами checkbox и label


### Mail ###

Система обработки почтовых событий.

  * В app.yml указываем все события (sfEvent), при инициализации которых мы
    должны отправить письмо:

        [yml]
        mail:
          events:
            user.register_success: [RegisterAdminMail, RegisterClientMail]

  * Создаем свои классы почтовых шаблонов (RegisterAdminMail, RegisterClientMail):

        [php]
        class RegisterAdminMail extends \sfMainPlugin\Mail\MessageTemplate
        {
            public function getMessage(\sfEvent $event)
            {
                $user = $event->getSubject();
                $message = $this->createTemplateMessage();

                $message->setFrom(...);
                $message->setSubject(...);
                $message->setTo(...);
                $message->setBody($this->getPartial('global/mail/user.register.admin.txt', array('user' => $user)));

                return $message;
            }
        }

  * Создаем директорию app/frontend/template/_mail и сохраняем туда почтовые партиалы

  * Кидаем необходимые события:

        [php]
        $dispatcher->notify(new sfEvent($user, 'user.register_success'));


### Log ###

  * **sfPhpLogger**  
    Пишет ошибки в PHP error_log. Включен по-умолчанию в для PROD.


### Helper ###

  * **sfMainCommonHelper**  
    Включен по-умолчанию
      * pluralForm($num, $name1, $name2, $name3) - Выбрать множественную форму склонения


Модули
------

 * Социальные виджеты

        [php]
        include_partial('sfMain/social', array(
            'host'        => 'http://my-site.com/',
            'vkontakteId' => 1234567,
        ));
