<?php
class EpiTemplate_Extendable extends EpiTemplate implements EpiTemplateInterface
{
  public function display($template = null, $vars = null)
  {
    $_template = Epi::getSetting('default_layout');

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