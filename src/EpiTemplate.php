<?php
class EpiTemplate implements EpiTemplateInterface
{
  const PHP_EXT = 'EpiTemplate_Extendable';
  const HAML    = 'EpiTemplate_MtHaml';
  private static $instances, $employ;

  /**
   * EpiRoute::display('/path/to/template.php', $array);
   * @name  display
   * @author  Jaisen Mathai <jaisen@jmathai.com>
   * @param string $template
   * @param array $vars
   * @method display
   * @static method
   */
  public function display($template = null, $vars = null)
  {
    if(is_array($vars))
    {
      extract($vars);
    }
    $templateInclude = Epi::getPath('view') . '/' . $template;
    if(is_file($templateInclude))
    {
      include $templateInclude;
    }
    else if (is_file($template)) {
      include $template;
    }
    else
    {
      EpiException::raise(new EpiException("Could not load template: {$templateInclude}", 404));
    }
  }
  
  /**
   * EpiRoute::get('/path/to/template.php', $array);
   * @name  get
   * @author  Jaisen Mathai <jaisen@jmathai.com>
   * @param string $template
   * @param array $vars
   * @method get
   * @static method
   */
  public function get($template = null, $vars = null)
  {
    $templateInclude = Epi::getPath('view') . '/' . $template;
    if(is_file($templateInclude))
    {
      if(is_array($vars))
      {
        extract($vars);
      }
      ob_start();
      include $templateInclude;
      $contents = ob_get_contents();
      ob_end_clean();
      return $contents;
    }
    else
    {
      EpiException::raise(new EpiException("Could not load template: {$templateInclude}", 404));
    }
  }
  
  /**
   * EpiRoute::json($variable); 
   * @name  json
   * @author  Jaisen Mathai <jaisen@jmathai.com>
   * @param mixed $data
   * @return string
   * @method json
   * @static method
   */
  public function json($data)
  {
    if($retval = json_encode($data))
    {
      return $retval;
    }
    else
    {
      $dataDump = var_export($dataDump, 1);
      EpiException::raise(new EpiException("json_encode failed for {$dataDump}", 404));
    }
  }
  
  /**
   * EpiRoute::jsonResponse($variable); 
   * This method echo's JSON data in the header and to the screen and returns.
   * @name  jsonResponse
   * @author  Jaisen Mathai <jaisen@jmathai.com>
   * @param mixed $data
   * @method jsonResponse
   * @static method
   */
  public function jsonResponse($data)
  {
    $json = self::json($data);
    header('X-JSON: (' . $json . ')');
    header('Content-type: application/x-json');
    echo $json;
  }

  /*
   * @param  type  required
   * @params optional
   */
  public static function getInstance()
  {
    $params = func_get_args();
    $hash   = md5(json_encode($params));
    if(isset(self::$instances[$hash]))
      return self::$instances[$hash];

    $type = $params[0];
    if(!isset($params[1]))
      $params[1] = array();
    self::$instances[$hash] = new $type($params[1]);
    self::$instances[$hash]->hash = $hash;
    return self::$instances[$hash];
  }

  /*
   * @param  $const
   * @params optional
   */
  public static function employ(/*$const*/)
  {
    if(func_num_args() === 1)
      self::$employ = func_get_arg(0);
    elseif(func_num_args() > 1)
      self::$employ = func_get_args();

    return self::$employ;
  }
}

interface EpiTemplateInterface
{
  public function get($key = null);
  public function display($template = null, $vars = null);
  public function json($data);
  public function jsonResponse($data);
}

function getTemplate()
{
  $employ = EpiTemplate::employ();
  $class = array_shift($employ);
  if(class_exists($class)) {
    return EpiTemplate::getInstance($class, $employ);
  } else if(class_exists(EpiTemplate::PHP_EXT)) {
    return EpiTemplate::getInstance(EpiTemplate::PHP_EXT);
  } else {
    return new EpiTemplate();
  }
}