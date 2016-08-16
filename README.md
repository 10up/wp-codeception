# WP Codeception
This is a WordPress Plugin which integrates with the [Codeception](http://codeception.com/) PHP testing framework and allows you to write and run Codeception tests for WordPress via WP CLI.

We're working towards supporting all of [Codeceptions commands](http://codeception.com/docs/reference/Commands). If you find one we haven't included yet, please submit a [Pull Request](https://github.com/10up/wp-codeception/pulls)!

<a href="http://10up.com/contact/"><img src="https://10updotcom-wpengine.s3.amazonaws.com/uploads/2016/08/10up_github_banner-2.png" width="850"></a>

## Installation
[Download the latest version](https://github.com/10up/wp-codeception/archive/master.zip) and extract, or clone the repository with Git into a new directory `wp-content/plugins/wp-codeception` in your WordPress install.

#### Install required node modules and composer dependencies

We'll run our commands from within [VVV](https://github.com/Varying-Vagrant-Vagrants/VVV) because WP CLI, Node, and Composer are already installed for us there.

```Bash
$ vagrant up
$ vagrant ssh
$ sudo apt-get install openjdk-7-jre-headless
$ cd /srv/www/yoursite/htdocs/wp-content/plugins/wp-codeception
$ composer install
$ wp plugin activate wp-codeception
```

Afterwards you'll have a new `vendor` directory within your `plugins/wp-codeception` directory which contains all the code libraries we're dependant on.

#### Install as a composer dependency

There is an alternative way to install this plugin. You can add it as a composer dependency for your project. To do it, run following command in your terminal:

```Bash
$ composer require 10up/wp-codeception
```

This command will install the plugin and all its dependencies for your project. Please, pay attention that if you already use [composer/installers](https://github.com/composer/installers) dependency in your project, then `wp-codeception` will be installed into `<PROJECT_DIR>/wp-content/plugins/wp-codeception/` folder. It happens, because `wp-codeception` has `wordpress-plugin` type and will be processed by `composer/installers` accordingly (read its documentation for more details).

If you want to add it as a dependency to your plugin or theme, you will need to update your `composer.json` file and tell it where to install `wp-codeception`. You can achieve it by providing `installer-paths` instructions like in the snippet below.

```Bash
{
    ...,
    "extra": {
        "installer-paths": {
            "vendor/{$name}/": ["type:wordpress-plugin"]
        }
    },
    ...
}
```

Now `composer/installers` will know to install wordpress plugins into *vendor* folder. The final step which you need to do is to update your `autoload` section and add `wp-codeception.php` file to the autoload files list.

```Bash
{
    ...,
    "autoload": {
        "psr-X": {
            ...
        },
        "files": [
            ...,
            "vendor/wp-codeception/wp-codeception.php"
        ]
    },
    ...
}
```

#### Install the test suite

See the [Codeception bootstrapping documentation](http://codeception.com/docs/reference/Commands#Bootstrap) for further information.

```Bash
# You'll create the test suite in your own plugin or theme directory..
$ cd /srv/www/yoursite/htdocs/wp-content/{your plugin or theme directory}
$ wp codeception bootstrap
```

Afterwards you'll have a new `tests` directory within your plugin or theme directory. This is your new test suite, and where you'll write all your tests.

## Writing Tests
You can write tests using any of the three [Codeception](http://codeception.com/) testing frameworks: [Acceptance](http://codeception.com/docs/03-AcceptanceTests), [Functional](http://codeception.com/docs/04-FunctionalTests) and [Unit](http://codeception.com/docs/05-UnitTests) testing. If you look within the new `tests` directory you'll see three config files; one for each test framework (acceptance.suite.yml, functional.suite.yml, unit.suite.yml). Edit these files as you wish.

#### Generate your first test
```Bash
# You should be in the plugin or theme directory where you ran the bootstrap
$ wp codeception generate-(cept|cest) (acceptance|functional|unit) MyTestName

# Example
$ wp codeception generate-cept acceptance LoginTest
```

Afterwards you'll have a new file in your plugin or theme directory `tests/acceptance/LoginTest.php`, where you can write your first test. Remember, any Codeception test will work here! For example, you could run any of the [acceptance test examples](http://codeception.com/docs/03-AcceptanceTests) mentioned in the Codeception documentation. Likewise, the same goes for [Functional](http://codeception.com/docs/04-FunctionalTests) and [Unit tests](http://codeception.com/docs/05-UnitTests).

#### Example: Writing a Login Acceptance Test
```PHP
<?php

// Make sure you've added your site URL to acceptance.suite.yml
// @see http://codeception.com/docs/03-AcceptanceTests#PHP-Browser
$I = new AcceptanceTester( $scenario );
$I->wantTo( 'Ensure WordPress Login Works' );

// Let's start on the login page
$I->amOnPage( wp_login_url() );

// Populate the login form's user id field
$I->fillField( 'input#user_login', 'YourUsername' );

// Popupate the login form's password field
$I->fillField( 'input#user_pass', 'YourPassword' );

// Submit the login form
$I->click( 'Log In' );

// Validate the successful loading of the Dashboard
$I->see( 'Dashboard' );
```

## Running Your Tests
Now you've written some tests, it's time to run them! But first..

#### Selenium
If you've created any browser automation/acceptance tests you'll need to turn [Selenium](http://www.seleniumhq.org/) on, and likewise, you'll want to stop Selenium after you're through running tests.

```Bash
# You can run these commands from anywhere in your WordPress install
$ wp selenium start

# Stop Selenium when you're through
$ wp selenium stop
```

#### Run
You'll use the `run` command to execute your tests from within your plugin or theme directory (where you ran the bootstrap). We've implemented most of the [Codeception 'run' command](http://codeception.com/docs/reference/Commands#Run) arguments, but if you find one we've missed please submit a [Pull Request](https://github.com/10up/wp-codeception/pulls)!

```Bash
# You should be in the plugin or theme directory where you ran the bootstrap
$ wp codeception run
```

#### Example: Running our Login Test
```Bash
# You should be in the plugin or theme directory where you ran the bootstrap
# Let's display verbose output
$ wp codeception run -vvv

Codeception PHP Testing Framework v2.0.11
Powered by PHPUnit 4.5.1 by Sebastian Bergmann and contributors.

  Rebuilding AcceptanceTester...

Acceptance-production Tests (1) ---------------------------------
Modules: WebDriver, WordPress, AcceptanceHelper
-----------------------------------------------------------------
Ensure WordPress Login Works (LoginTest)
Scenario:
* I am on page "http://site.com/wp-login.php"
* I fill field "input#user_login","YourUsername"
* I fill field "input#user_pass","YourPassword"
* I click "Login"
* I see "Dashboard"
 PASSED
```
