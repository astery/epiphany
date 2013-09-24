<?php
class EpiTemplate_MtHaml extends EpiTemplate implements EpiTemplateInterface
{
  private $haml;

  public function __construct() {
    $this->haml = new MtHaml\Environment('php');
  }

  public function display($template = null, $vars = null)
  {
    $template = $this->getCompiledFile($template);
    return parent::display($template, $vars);
  }

  public function get($template = null, $vars = null)
  {
    $template = $this->getCompiledFile($template);
    return parent::display($template, $vars);
  }

  private function getCompiledFile($template) {
    $template = Epi::getPath('view').'/'.$template;
    $complied = $template.'.php';
    $hamlCode = file_get_contents($template);

    // no need to compile if already compiled and up to date
    if (!file_exists($complied) || filemtime($complied) != filemtime($template)) {

        $phpCode = $this->haml->compileString($hamlCode, $template);

        $tempnam = tempnam(dirname($template), basename($template));
        file_put_contents($tempnam, $phpCode);
        rename($tempnam, $complied);
        touch($complied, filemtime($template));
    }

    return $complied;
  }
}