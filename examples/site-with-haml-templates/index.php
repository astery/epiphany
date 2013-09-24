<?php
chdir('..');
require_once '../src/Epi.php';
require_once 'mthaml/lib/MtHaml/Autoloader.php';
MtHaml\Autoloader::register();

Epi::setPath('base', '../src');
Epi::setPath('view', 'site-with-haml-templates');
Epi::init('route','template-mthaml');

/*
 * This is a sample page whch uses EpiCode.
 * There is a .htaccess file which uses mod_rewrite to redirect all requests to index.php while preserving GET parameters.
 * The $_['routes'] array defines all uris which are handled by EpiCode.
 * EpiCode traverses back along the path until it finds a matching page.
 *  i.e. If the uri is /foo/bar and only 'foo' is defined then it will execute that route's action.
 * It is highly recommended to define a default route of '' for the home page or root of the site (yoursite.com/).
 */
getRoute()->get('/', array('MyClass', 'MyMethod'));
getRoute()->run(); 

/*
 * ******************************************************************************************
 * Define functions and classes which are executed by EpiCode based on the $_['routes'] array
 * ******************************************************************************************
 */
class MyClass
{
  static public function MyMethod()
  {
    $params = array();
    $params['heading'] = 'Hello friends!';
    $params['friends'] = array('John', 'Mike', 'Luke', 'Jack', 'Spike');

    getTemplate()->display('sample-template.haml', $params);
  }
}
