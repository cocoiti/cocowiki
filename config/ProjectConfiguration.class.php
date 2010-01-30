<?php

require_once '/usr/share/pear/symfony/autoload/sfCoreAutoload.class.php';
ini_set('include_path', ini_get('include_path') . ':' .realpath(dirname(__FILE__) . '/../lib/git/'));
sfCoreAutoload::register();

class ProjectConfiguration extends sfProjectConfiguration
{
  public function setup()
  {
    $this->enablePlugins('sfDoctrinePlugin');
    $this->enablePlugins('sfTwigPlugin');
    $this->dispatcher->connect('template.twig_template_paths', array($this, 'getTwigTemplatePaths'));
    $this->dispatcher->connect('template.twig_get_extensions', array($this, 'getTwigExtensions'));
  }

  public function getTwigTemplatePaths(sfEvent $event, $params)
  {
    $layout = array_filter(sfContext::getInstance()->getConfiguration()
                                            ->getDecoratorDirs(), 'file_exists');
    return array_merge($layout, $params);
  }

  public function getTwigExtensions(sfEvent $event, $params)
  {
    $escaper = new Twig_Extension_Escaper(true);
    return array_merge($params, array($escaper));
  }

}
