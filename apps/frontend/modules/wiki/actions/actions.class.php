<?php

/**
 * wiki actions.
 *
 * @package    cocowiki
 * @subpackage wiki
 * @author     cocoiti
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class wikiActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)
  {
    $wiki = new myWiki();
    $pagename = $this->getRequestParameter('pagename', sfConfig::get('app_wiki_default'));
  
    $text = $wiki->getText($pagename);
    if($text  === false) {
      $this->forward404($pagename);
    }
    $html = $wiki->convert($text);
    $this->pagename = $pagename;
    $this->wiki = $html;
  }
}
