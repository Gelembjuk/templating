## Gelembjuk/Templating

Common interface for different PHP templating engines like Smarty. This package allows to switch between engines witout changes in our PHP code.

The package was created to be able to migrate to different popular PHP templating engines without need to change my application code.

### Installation
Using composer: [gelembjuk/templating](http://packagist.org/packages/gelembjuk/templating) ``` require: {"gelembjuk/templating": "dev-master"} ```

### Configuration

Configuration is done in run time with a constructor options (as hash argument)

#### Configure FileLogger 

**logfile** path to your log file (where to write logs)
**groupfilter** list of groups of events to log. `all` means log everything. Groups separated with **|** symbol

```php
$logger1 = new Gelembjuk\Logger\FileLogger(
	array(
		'logfile' => $logfile,  // path to your log file (where to write logs)
		'groupfilter' => 'group1|group2|group3'  // list of groups of events to log. `all` means log everything
	));

```



### Usage

#### FileLogger

```php

require '../vendor/autoload.php';

$logger1 = new Gelembjuk\Logger\FileLogger(
	array(
		'logfile' => '/tmp/log.txt',
		'groupfilter' => 'all' // log everything this time
	));

// do test log write
$logger1->debug('Test log',array('group' => 'test'));

$logger1->setGroupFilter('group1|group2'); // after this only group1 and group2 events are logged

$logger1->debug('This message will not be in logs as `test` is out of filter',array('group' => 'test'));

```
#### ApplicationLogger trait

```php

require '../vendor/autoload.php';

class A {
}

class B extends A {
	// include the trait to have logging functionality in this class
	use Gelembjuk\Logger\ApplicationLogger;
	
	public function __construct($logger) {
		$this->setLogger($logger);

		$this->logQ('B object create','construct|B');
	}

	public function doSomething() {
		$this->logQ('doSomething() in B','B');
	}
}

class C {
	use Gelembjuk\Logger\ApplicationLogger;
	
	public function __construct($logger) {
		$this->setLogger($logger);

		$this->logQ('C object create','construct|C');
	}

	public function doOtherThing() {
		$this->logQ('oOtherThing() in C','C');
	}
}

$b = new B($logger1); // $logger1 is instance of FileLogger
$c = new C($logger1);

$b->doSomething();
$c->doOtherThing();

```

#### ErrorScreen

```php

require '../vendor/autoload.php';

$errors = new Gelembjuk\Logger\ErrorScreen(
		array(
			'logger' => $logger1 /*created before*/,
			'viewformat' => 'html',
			'catchwarnings' => true,
			'catchfatals' => true,
			'showfatalmessage' => true,
			'commonerrormessage' => 'Sorry, somethign went wrong. We will solve ASAP'
		)
	);

// to catch exceptions on the top level of the app
try {
	// do something 
	
} catch (Exception $e) {
	$errors->processError($e);
}

// presume there was no exception
// now catch warning

// warning is raised and catched in errors object
// error message displayed to a user
include('not_existent_file.php'); 	

```

### Author

Roman Gelembjuk (@gelembjuk)

