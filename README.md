<!--suppress ALL -->

<p align="center">
    <a href="https://www.magiedit.com/" target="_blank">
        <img src="LOGO.png" alt="Logo of MagiEdit" width="700" height="272">
    </a>
</p>

**A web application for creating paragraph games and visual novels.**

- [Requirements](#requirements)
- [Deploy](#deploy)
  * [Prepare](#prepare)
  * [Install](#install)
  * [Verify](#verify)
- [Configure](#configure)
  * [Environment](#environment)
    * [General](#general)
    * [Production](#production)
  * [Database](#database)
  * [Data](#data)
  * [Mail](#mail)
- [Launch](#launch)
- [Test](#test)
  * [Introduction](#introduction)
  * [Acceptance](#acceptance)
  * [Code](#code)
  * [API](#api)

<small><i><a href='http://ecotrust-canada.github.io/markdown-toc/'>Table of contents generated with markdown-toc</a></i></small>

---

## Requirements

The following components are required and must be installed:

1. Local LAMP web server, e.g.: [XAMPP](https://www.apachefriends.org/index.html) equipped with a PHP interpreter in at least version **8.2.0**.

2. Dependency manager [Composer](http://getcomposer.org/).

3. [Git for Windows](https://gitforwindows.org/).

4. Modern web browser, e.g. Firefox, Edge, Chrome, Safari (preferably -- in the latest version).

The application has been tested only in **Windows 11 Pro**, under the control of the XAMPP server and the **PHP 8.2.4** interpreter.

## Deploy

### Prepare

If [XAMPP](https://www.apachefriends.org/index.html) (or another LAMP-type package) is not installed, you can download it
from [ApacheFriends.org](https://www.apachefriends.org/download.html).

If you do not have [Git for Windows](https://gitforwindows.org/), you can download it from [this page](https://gitforwindows.org/).

If you don't have [Composer](http://getcomposer.org/), you can install it using [this guide](https://getcomposer.org/doc/00-intro.md#installation-windows).

### Install

Clone the main repository to a folder of your choice on your local disk:

```bash
git clone git@github.com:niner-games/magiedit.web.git
```

Update all components:

```bash
cd magiedit.web
composer update
```

The `-ignore-platform-req=php` flag should only be used if you have [PHP version 8.2 or later](https://forum.yiiframework.com/t/current-version-of-yii-2-not-ready-for-php-8-2/135156/2?u=trejder).

Initialize the production environment:

```bash
php init --env=Production --overwrite=All --delete=All
```

If you want to use a _root_ folder other than the default one, you need to [configure it](https://www.yiiframework.com/extension/yiisoft/yii2-app-advanced/doc/guide/2.0/en/start-installation#preparing-application).

### Verify

Start the web server and the MySQL / MariaDB database server.

If you are using Windows, make sure that your **web server is always started with administrator privileges**.

This is especially important in development and test environments. Without this, the application will not be able to read the _git
commit hash_ and _git tag number_. And it may show an incorrect or empty version of the application.

Navigate to the folder where the application is installed in your web browser. Double check that your environment
meets the minimum requirements:

[`http://localhost/niner-games/magiedit.web/requirements.php`](http://localhost/niner-games/magiedit.web/requirements.php)

Check all errors and warnings carefully. Before continuing, resolve any issues that you can resolve.

## Configure

### Environment

#### General

Open the configuration file `common/config/main-local.php` and provide the name of the current environment, for example:

```php
putenv('ENVIRONMENT_NAME=prod');
```

This is for informational reasons only (added to application version in the footer), but it is important to provide it.
You can use any name instead of `prod`. For example: `dev`, `test`, `pre-prod`, `uat`, `backup` etc.

#### Production

If you have [initialized application in production mode](#install) then there's an extra `.htaccess` file generated in the
root folder to make sure that this root folder (i.e. domain) correctly points to `/frontend/web` folder, which is (or should
be) the only web-accessible folder in the entire application. You may want to check, if settings is this file does not break
your server's configuration.

In addition, there's a `baseUrl` property of `request` and `urlManager` components, nullified to empty string (`''`) in
`fronted/config/main-local.php`. It is made so for the same reasons as above. You may want to check and possibly change 
that file as well.

### Database

Create the database manually (using the software of your choice) or by executing the following SQL command:

```sql
CREATE DATABASE magiedit;
```

Create a separate user with at least `SELECT`, `INSERT`, `UPDATE`, `DELETE`, `CREATE`, `ALTER`,
`INDEX` and `DROP` privileges for this database:

```sql
CREATE USER 'scripts'@'localhost' IDENTIFIED VIA mysql_native_password USING 'P#gtitO@D:=~5C.7?IDyF1?dZ2Yo|*'; GRANT SELECT, INSERT, UPDATE, DELETE, CREATE, DROP, INDEX, ALTER ON *.* TO 'scripts'@'localhost' REQUIRE NONE WITH MAX_QUERIES_PER_HOUR 0 MAX_CONNECTIONS_PER_HOUR 0 MAX_UPDATES_PER_HOUR 0 MAX_USER_CONNECTIONS 0;
```

(change password `'P#gtitO@D:=~5C.7?IDyF1?dZ2Yo|*` [to different one](https://randomkeygen.com/) in above command)

Open file `common/config/main-local.php` and provide correct database server configuration, for example:

```php
$dbScheme = 'mysql';
$dbHost = 'localhost';
$dbName = 'magiedit';
$dbUsername = 'scripts';
$dbPassword = 'P#gtitO@D:=~5C.7?IDyF1?dZ2Yo|*';
$dbCharset = 'utf8';
```

(change the password `'P#gtitO@D:=~5C.7?IDyF1?dZ2Yo|*` above to the one defined when creating the user)

### Data

Run all available data migrations:

```bash
php yii migrate
```

The database **must** be manually created by the user (two steps earlier) for the migrations to work.

### Mail

You need to have a properly configured MX server (mail exchange server) to:

- register a new user (→ send an email address verification message),
- reset a forgotten password (→ send a password reset token).

Please open the file `common/config/main-local.php` and provide the correct MX server configuration, for example:

```php
$mailScheme = 'smtp';
$mailHost = 'poczta.o2.pl';
$mailUsername = 'username@o2.pl';
$mailPassword = 'strong123P@SSWORD!';
$mailPort = '587';
```

Then go to `frontend/config/params-local.php` and set the correct value for the `supportEmail` parameter:

```php
'supportEmail' => 'username@o2.pl'
```

Most mail servers require that you use **exactly the same values** (i.e. the same e-mail address) in all these places:

- for `$mailUsername` in `common/config/main-local.php`
- for `senderEmail` in `common/config/params-local.php`
- for `supportEmail` in `frontend/config/params-local.php`

I.e. you can only send emails using your account email address.

Here is a couple of links, in case you would have troubles configuring MX:

* using regular email accounts ([Using Built-in Transports](https://symfony.com/doc/current/mailer.html#using-built-in-transports)):
  * [Gmail](https://support.google.com/mail/answer/56256?hl=en)
  * [Outlook](https://www.microsoft.com/en-us/microsoft-365-life-hacks/organization/how-to-create-outlook-email-account#:~:text=Creating%20an%20Outlook%20account%20is%20easy%3A%201%20Go,email%20address.%20...%203%20Choose%20a%20username.%20)
  * [iCloud](https://support.apple.com/guide/icloud/create-a-primary-icloudcom-email-address-mmdd8d1c5c/icloud)
* using specialized email service ([Using a 3rd Party Transport](https://symfony.com/doc/current/mailer.html#using-a-3rd-party-transport)):
  * [Amazon Simple Email Service](https://aws.amazon.com/ses/pricing/)
  * [Mailchimp Transactional Email](https://mailchimp.com/pricing/transactional-email/)

A technical email sent from this application may be considered as SPAM and blocked by your MX server. Or not delivered by
any other reason. In this case, check the application logs for possible errors. If you're sending emails from a development
environment, you can see each mail prepared for send, as it has a local copy stored in file, in `frontend/runtime/debug/mail`
folder.

### Parameters

Finally, go to `common/config/params-local.php` and adjust the application configuration to your needs:

```php
'senderEmail' => 'username@o2.pl',
'senderName' => 'MagiEdit',
'user.passwordResetTokenExpire' => 3600,
'user.passwordMinLength' => 8
```

## Launch

Your app should be ready to use. Launch your browser and go to:

[`http://localhost/niner-games/magiedit.web/frontend/web/`](http://localhost/niner-games/magiedit.web/frontend/web/)

For more detailed testing instructions and technical documentation, visit the [project wiki](https://github.com/niner-games/magiedit.web/wiki).

## Test

### Introduction

Tests are located in `tests` directory. They are developed with [Codeception PHP Testing Framework](https://codeception.com/).
By default, there are 3 test suites:

- `unit`
- `functional`
- `acceptance`

Tests can be executed by running

```
vendor/bin/codecept run
```

The command above will execute unit and functional tests. Unit tests are testing the system components, while functional
tests are for testing user interaction. Acceptance tests are disabled by default as they require additional setup since
they perform testing in real browser. 

### Acceptance

To execute acceptance tests do the following:  

1. Rename `tests/acceptance.suite.yml.example` to `tests/acceptance.suite.yml` to enable suite configuration

2. Replace `codeception/base` package in `composer.json` with `codeception/codeception` to install full-featured
   version of Codeception

3. Update dependencies with Composer 

    ```
    composer update  
    ```

4. Download [Selenium Server](https://www.seleniumhq.org/download/) and launch it:

    ```
    java -jar ~/selenium-server-standalone-x.xx.x.jar
    ```

    In case of using Selenium Server 3.0 with Firefox browser since v48 or Google Chrome since v53 you must download [GeckoDriver](https://github.com/mozilla/geckodriver/releases) or [ChromeDriver](https://sites.google.com/a/chromium.org/chromedriver/downloads) and launch Selenium with it:

    ```
    # for Firefox
    java -jar -Dwebdriver.gecko.driver=~/geckodriver ~/selenium-server-standalone-3.xx.x.jar
    
    # for Google Chrome
    java -jar -Dwebdriver.chrome.driver=~/chromedriver ~/selenium-server-standalone-3.xx.x.jar
    ``` 
    
    As an alternative way you can use already configured Docker container with older versions of Selenium and Firefox:
    
    ```
    docker run --net=host selenium/standalone-firefox:2.53.0
    ```

5. (Optional) Create `yii2basic_test` database and update it by applying migrations if you have them.

   ```
   tests/bin/yii migrate
   ```

   The database configuration can be found at `config/test_db.php`.


6. Start web server:

    ```
    tests/bin/yii serve
    ```

7. Now you can run all available tests

   ```
   # run all available tests
   vendor/bin/codecept run

   # run acceptance tests
   vendor/bin/codecept run acceptance

   # run only unit and functional tests
   vendor/bin/codecept run unit,functional
   ```

### Code

By default, code coverage is disabled in `codeception.yml` configuration file, you should uncomment needed rows to be able
to collect code coverage. You can run your tests and collect coverage with the following command:

```
#collect coverage for all tests
vendor/bin/codecept run --coverage --coverage-html --coverage-xml

#collect coverage only for unit tests
vendor/bin/codecept run unit --coverage --coverage-html --coverage-xml

#collect coverage for unit and functional tests
vendor/bin/codecept run functional,unit --coverage --coverage-html --coverage-xml
```

You can see code coverage output under the `tests/_output` directory.

### API

To test the API, it's recommended to use the [Insomnia Portable](https://portapps.io/app/insomnia-portable/) application.

You can import the file _insomnia.json_  (available in the _console/migrations_ folder of the project)  into your copy of
Insomnia Portable (note: this may not work with the standalone Insomnia version).  This will prepare all the environments 
(DEV, TEST, UAT, PROD) and API endpoints for testing.