## Gelembjuk/Templating

Common interface for different PHP templating engines like Smarty, Twig. This package allows to switch between engines witout changes in your PHP code.

The package was created to be able to migrate to different popular PHP templating engines without need to change my application code. However, it is still needed to modify templates.

### Installation
Using composer: [gelembjuk/templating](http://packagist.org/packages/gelembjuk/templating) ``` require: {"gelembjuk/templating": "1.*"} ```

### Configuration

Configuration is done in run time with a constructor options (as hash argument)


```php
$templateroptions = array(
		'templatepath' => $templatesdir, // directory where templates are stored
		'compiledir' => $compiledir,    // directory to use for compilation temp files. It is needed fro Smarty, but not Twig 
		'usecache' => false,       // don't cache . If true then use cache
		'cachedir' => $cachedir,   // path to cache directory. used if `usecache` is true
		'extension' => 'htm' // our templates files will have htm extension
	);
```

### Usage


```php

require '../vendor/autoload.php';

$templater = new Gelembjuk\Templating\SmartyTemplating();
// $templater = new Gelembjuk\Templating\TwigTemplating();

// init template engine
$templater->init($templateroptions);

$templater->setVar('firstname',"John");
$templater->setVar('lastname',"Smith");

$templater->setTemplate('homepage');

// fetch page page content
$pagecontent = $templater->fetchTemplate();

echo $pagecontent;

```
File **homepage.htm** in case of Smarty

```html
<div>

<h1>Hello {$lastname}, {$firstname} </h1> 

</div>

```

File **homepage.htm** in case of Twig

```html
<div>

<h1>Hello {{ lastname }}, {{ firstname }} </h1> 

</div>

```

### Author

Roman Gelembjuk (@gelembjuk)

