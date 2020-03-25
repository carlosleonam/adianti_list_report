# **Generator Report ( HTML, PDF, RTF and XLS ) for Adianti in TDatagrid**
> Generate report direct from TDatagrid
<!--
[![NPM Version][npm-image]][npm-url]
[![Build Status][travis-image]][travis-url]
[![Downloads Stats][npm-downloads]][npm-url]
-->

What is it? Simple! This is a extended classes from Adianti reports, for using inside TDatagrid class.
>O que é isso? Simples! Esta é uma classe estendida dos relatórios do Adianti, para uso dentro da classe TDatagrid.

We use this hack to get current columns width to adapt report for best preview, and use cookies for that.
>Usamos esse hack para obter a largura atual das colunas para adaptar o relatório para a melhor visualização e usamos cookies para isso.

![](header.png)

## **Installation**

### **Download:**

Download lastest release, and extract to a subdir in 'app/lib', like 'sislib' or whatever you want.


**Linux:**

```sh
cd /app/lib
mkdir sislib // or witchever you prefer (but make manual adapter on source code)
```

**Windows:**

```sh
cd \app\lib
md sislib // or witchever you prefer
```

**Adianti Builder:**

Pressumed that you has knowleadge how Builder works. So, got ahead:

With previous download zip file with package, descompact in local computer folder. The idea is to create same classes inside your Builder project



### Composer:
> Remember, Adianti Framework has a particular method to load your builtin classes. Cause of that, the installation of this generator has following the above passes.

A little bit more complex, but it works:

Insert this below lines, in appropriate local, inside your compose.json (**please, remember backup you "composer.json" file before!**)

```sh
{
    "repositories": [
       { "type": "git", "url": "https://github.com/carlosleonam/listreport" }
    ],


    "extra": {
        "installer-paths":{
        "app/lib/sislib/": ["carlosleonam/listreport"]
        }
    }
}
```

After that, install package for personalize install directory:

```sh
composer require mnsami/composer-custom-directory-installer
```

And finally, install our package:
```sh
composer require carlosleonam/adianti_list_report
```

## Usage example


### In your class that has TDatagrid inside it

Insert this below code in appropriate place in your class (in general, before __construct method). See example:

```php
class MyClass extends TPage
{
    private $form; // form
    private $datagrid; // listing
    private $pageNavigation;
    private $loaded;
    private $filter_criteria;
    private static $database = 'database_conector';
    private static $activeRecord = 'MyModel';
    private static $primaryKey = 'id';
    private static $formName = 'formList_MyClass';
    private $showMethods = ['onReload', 'onSearch'];

    #------[ BEGIN CODE TO INSERT ]---------------------------------
    // Number of buttons column's
    private static $buttons_columns = 1;
    // Array of position of columns that has total
    private static $columns_with_total = [6];
    // Trait to HTML, PDF, RTF and XLS
    use TGeneratorReportTrait;
    #------[ /END CODE TO INSERT  ]---------------------------------
    /**
     * Class constructor
     * Creates the page, the form and the listing
     */
```

And, before end of __construct method, insert this lines:

```php

        #------[ BEGIN CODE TO INSERT ]---------------------------------
        include_once('app/lib/sislib/src/ReportButtons.php');
        include_once('app/lib/sislib/src/tdatagrid_colwidth.php');
        #------[ /END CODE TO INSERT  ]---------------------------------

        parent::add($container);

    }
```

Now, before update Composer ou download in appropriate folder (app/lib/sislib/src), reload your class and enjoy.

... this is incomplete README.md, because it's only for me use. I'll try make better docs when I'll have time. Sorry!

<!--
A few motivating and useful examples of how your product can be used. Spice this up with code blocks and potentially more screenshots.

_For more examples and usage, please refer to the [Wiki][wiki]._

## Development setup

Describe how to install all development dependencies and how to run an automated test-suite of some kind. Potentially do this for multiple platforms.

```sh
make install
npm test
```

## Release History

* 0.2.1
    * CHANGE: Update docs (module code remains unchanged)
* 0.2.0
    * CHANGE: Remove `setDefaultXYZ()`
    * ADD: Add `init()`
* 0.1.1
    * FIX: Crash when calling `baz()` (Thanks @GenerousContributorName!)
* 0.1.0
    * The first proper release
    * CHANGE: Rename `foo()` to `bar()`
* 0.0.1
    * Work in progress

## Meta

Your Name – [@YourTwitter](https://twitter.com/dbader_org) – YourEmail@example.com

Distributed under the XYZ license. See ``LICENSE`` for more information.

[https://github.com/yourname/github-link](https://github.com/dbader/)

## Contributing

1. Fork it (<https://github.com/yourname/yourproject/fork>)
2. Create your feature branch (`git checkout -b feature/fooBar`)
3. Commit your changes (`git commit -am 'Add some fooBar'`)
4. Push to the branch (`git push origin feature/fooBar`)
5. Create a new Pull Request

<~!-- Markdown link & img dfn's --~>
[npm-image]: https://img.shields.io/npm/v/datadog-metrics.svg?style=flat-square
[npm-url]: https://npmjs.org/package/datadog-metrics
[npm-downloads]: https://img.shields.io/npm/dm/datadog-metrics.svg?style=flat-square
[travis-image]: https://img.shields.io/travis/dbader/node-datadog-metrics/master.svg?style=flat-square
[travis-url]: https://travis-ci.org/dbader/node-datadog-metrics
[wiki]: https://github.com/yourname/yourproject/wiki
-->