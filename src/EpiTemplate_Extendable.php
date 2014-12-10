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

    if (file_exists(Epi::getPath('view').'/'.$_template) || file_exists($_template)) {
      $vars['_content'] = $_content;
      return parent::display($_template, $vars);
    } else {
      echo $_content;
    }
  }
}