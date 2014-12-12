<?php
class EpiTemplate_Extendable extends EpiTemplate implements EpiTemplateInterface
{
  public function display($template = null, $vars = null)
  {
    $def_vars = Epi::getSetting('template_default_vars');

    if (is_array($def_vars)) {
      $vars = array_merge($def_vars, $vars);
    }

    if (array_key_exists('_template', $vars)) {
      $_template = $vars['_template'];
    }

    $_content = $this->get($template, $vars);

    if (is_file(Epi::getPath('view').'/'.$_template) || is_file($_template)) {
      $vars['_content'] = $_content;
      return parent::display($_template, $vars);
    } else {
      echo $_content;
    }
  }
}