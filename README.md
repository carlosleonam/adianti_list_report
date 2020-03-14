# Generator Report ( html, pdf, rtf ) for Adianti in TDatagrid
> Generate report direct from TDatagrid

<!-- [![NPM Version][npm-image]][npm-url] -->
<!-- [![Build Status][travis-image]][travis-url] -->
<!-- [![Downloads Stats][npm-downloads]][npm-url] -->

What is it? Simple! This is a extended classes from Adianti reports, for using inside TDatagrid class.

We use a hack to get current columns width to adapt report for best preview, and it use cookies for that.

![](header.png)

## Installation

### Download:

Download lastest release, and extract to a subdir in 'app/lib', like 'sislib' or whatever you want.

**Linux:**

```sh
cd /app/lib
mkdir sislib // or witchever you prefer
```

**Windows:**

```sh
cd \app\lib
md sislib // or witchever you prefer
```

### Composer:

A little bit more complex, but it works:

Insert this below lines in appropriate local in your compose.json (**please, remember backup you "composer.json" file before!**)

```sh
{
  "repositories": [
    {
      "name": "carlosleonam/listreport",
      "type": "git",
       "url": "https://github.com/carlosleonam/listreport"
    }
  ],
    "require": {
    "carlosleonam/listreport": "dev-master"
    },
    "extra": {
      "installer-paths":{
        "app/lib/sislib/": ["carlosleonam/listreport"]
        }
    }
}

```

## Usage example

Remember, Adianti Framework has a particular method to load your builtin classes. Cause of that, the installation of this generator has following the above passes.

### In your class that has TDatagrid inside it

**For HTML**

```php
    public function onGenerateHtml($param = null)
    {
        // Comment "if" code below if you don't want limit number of records send to report
        $result_test = $this->checkHugeQtRows();
        if ($result_test[0]) {
            if (is_string($result_test[1])) {
                gf::swalert('Alerta!', $result_test[1] ,'error');
            } else {
                gf::swalert('Excesso!','Mais de 1.000 registros selecionados!','error');
            }
            return false;
        }

        // Array Columns Width
        $array_widths = array_slice( gf::checkCookieForTDatagrid('profile_tdatagrid_'. self::$formName .'_col_width'), 2);
        $array_totals = $array_widths;
        $array_totals = array_fill( 0, count( $array_totals ), false );
        $array_totals[ count( $array_totals ) - 1 ] = true;
        $report_generator = new TGeneratorReport(
            self::$database,                                    // database name
            self::$activeRecord,                                // active record
            $this->datagrid->getColumns(),                      // columns of current TDatagrid
            'Listagem de ' . gf::getFormName( $this->form ),    // report title
            $this->onSearch( false ),                           // current filter (only works if you change "onSearch" method, see docs folder)
            'html',                                             // type of report desire
            null,
            $array_widths,                                      // array with columns width
            $array_totals                                       // column to get total (the last is default)
        );
    }

```

For PDF and RTF, its similar.

**Function 'checkHugeQtRows' to limit number of rows in report**

```php
    public function checkHugeQtRows()
    {
        $filters = $this->onSearch( false );
        if (!$filters) {
            $result = [];
            $result[] = true;
            $result[] = 'Não foi efetuada nenhuma Filtragem. Efetue uma busca antes de tentar imprimir!';
            return $result;
        }
        try
        {
            TTransaction::open(self::$database); // open a transaction
            $repository = new TRepository(self::$activeRecord);
            // creates a criteria
            $criteria = new TCriteria;
            if($filters = TSession::getValue(__CLASS__.'_filters'))
            {
                foreach ($filters as $filter)
                {
                    $criteria->add($filter);
                }
            }

            // load the objects according to criteria
            $objects_count = $repository->count($criteria);
            TTransaction::close(); // close the transaction
        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage()); // shows the exception error message
            TTransaction::rollback(); // undo all pending operations
        }
        if ($objects_count > 1000) { // Change if you need another
            $result = [];
            $result[] = true;
            $result[] = null; // Use if you need create a personal message
            return $result;
        } else {
            return false;
        }

    }

```

**Function to save cookies for TDatagrid**

I use it inside general functions class, but use it where you want or need.

```php
    public static function checkCookieForTDatagrid($cookie_name): array
    {
            if(isset($_COOKIE[ $cookie_name ])){
                $widths_1 = $_COOKIE[ $cookie_name ];;
                if ($widths_1 == '' ) {
                    $widths = [];
                } else {
                    $widths = explode(',', $widths_1);
                }
            } else {
                $widths = [];
            }
            $result = $widths;

        return $result;
    }

```

... this is incomplete README.md, because it's only for me use

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

<!-- Markdown link & img dfn's -->
[npm-image]: https://img.shields.io/npm/v/datadog-metrics.svg?style=flat-square
[npm-url]: https://npmjs.org/package/datadog-metrics
[npm-downloads]: https://img.shields.io/npm/dm/datadog-metrics.svg?style=flat-square
[travis-image]: https://img.shields.io/travis/dbader/node-datadog-metrics/master.svg?style=flat-square
[travis-url]: https://travis-ci.org/dbader/node-datadog-metrics
[wiki]: https://github.com/yourname/yourproject/wiki
-->