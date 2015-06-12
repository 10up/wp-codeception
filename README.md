# WP Codeception
This plugin integrates kthe [Codeception](http://codeception.com/) PHP testing framework into WordPress and allows you to run tests using WP CLI.

We're working towards supporting all of [Codeceptions commands](http://codeception.com/docs/reference/Commands). If you find one we haven't included yet, please submit a [Pull Request](https://github.com/10up/wp-codeception/pulls)!

## Installation
[Download the latest version](https://github.com/10up/wp-codeception/archive/master.zip) and extract, or clone the repository with Git into a new directory wp-content/plugins/wp-codeception in your WordPress install.

### Install required node modules and composer dependencies

We'll run our commands from within [VVV](https://github.com/Varying-Vagrant-Vagrants/VVV) because WP CLI, Node, and Composer are already installed for us there. 
 
```Bash
$ vagrant up
$ vagrant ssh
$ sudo apt-get install openjdk-7-jre-headless
$ cd /srv/www/yoursite/htdocs/wp-content/plugins/wp-codeception
$ wp plugin activate wp-codeception
$ npm install
$ composer install
```

Afterwards you'll have a new 'vendor/' directory within your plugins/wp-codeception directory which contains all the code libraries we're dependant on.

### Install the test suite

See the [Codeception bootstrapping documentation](http://codeception.com/docs/reference/Commands#Bootstrap) for further information.

```Bash
# You should still be in your plguins/wp-codeception directory
$ wp codeception bootstrap
```

Afterwards you'll have a new 'tests/' directory within the wp-codeception plugin. This is your new test suite, and where you'll write all your tests. 

## Writing Tests
You can write tests using any of the three [Codeception](http://codeception.com/) testing frameworks: Acceptance, Functional, and Unit Testing. If you look within the new 'tests/' directory you'll see three config files (acceptance.suite.yml, functional.suite.yml, unit.suite.yml). Edit these files as you wish  

*Generate your first test*
```Bash
# You should still be in your plguins/wp-codeception directory
$ wp codeception generate-(cept|cest) (acceptance|functional|unit) MyTestName

# Example
$ wp codeception generate-cept acceptance LoginTest
```

Afterwards you'll have a new file: `plugins/wp-codeception/tests/acceptance/LoginTest.php`, where you can write your first test. Remember, any Codeception test will work here! For example, you could run any of the [acceptance test examples](http://codeception.com/docs/04-AcceptanceTests) mentioned in the Codeception documentation. Likewise, the same goes for [Functional](http://codeception.com/docs/05-FunctionalTests) and [Unit tests](http://codeception.com/docs/06-UnitTests).

### Example: Writing Login Acceptance Test
```PHP
<?php

// Make sure you've added your site URL to acceptance.suite.yml
// @see http://codeception.com/docs/04-AcceptanceTests#PHP-Browser
$I = new AcceptanceTester( $scenario );
$I->wantTo( 'Ensure WordPress Login Works' );

// Let's start on the login page
$I->amOnPage( '/wp-login.php' );

// Popupate the login form's user id field
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

### Selenium
If you've created any browser automation/acceptance tests you'll need to turn [Selenium](http://www.seleniumhq.org/) on, and likewise, you'll want to stop Selenium after you're through running tests.

```Bash
# You should still be in your plguins/wp-codeception directory
$ wp selenium start

# Stop Selenium when you're through
$ wp selenium stop
```

### Run
You'll use the `run` command to execute your tests. We've implemented most of the [Codeception 'run' command](http://codeception.com/docs/reference/Commands#Run) arguments, but if you find one we've missed please submit a [Pull Request](https://github.com/10up/wp-codeception/pulls)! 
 
```Bash
# You should still be in your plguins/wp-codeception directory
$ wp codeception run
```

### Example: Running our Login Test
```Bash
# You should still be in your plguins/wp-codeception directory
# Let's display verbose output
$ wp codeception run -vvv

Codeception PHP Testing Framework v2.0.11
Powered by PHPUnit 4.5.1 by Sebastian Bergmann and contributors.

  Rebuilding AcceptanceTester...
  
Acceptance-production Tests (1) -----------------------------------------------------------------------------------------------------------------------
Modules: WebDriver, WordPress, AcceptanceHelper
-------------------------------------------------------------------------------------------------------------------------------------------------------
Ensure WordPress Login Works (LoginTest)
Scenario:
* I am on page "/wp-login.php"
* I fill field "input#user_login","YourUsername"
* I fill field "input#user_pass","YourPassword"
* I click "Login"
* I see "Dashboard"
 PASSED
```