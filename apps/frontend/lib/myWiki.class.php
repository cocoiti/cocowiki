<?php
require_once 'VersionControl/Git.php';
require_once 'HatenaSyntax.php';

//todo model class
class myWiki
{
  protected
    $updateFile = null, 
    $cacheDir  = null;
  public function __construct()
  {
    $this->cacheDir = sfConfig::get('sf_app_cache_dir') . '/myGit';
    $this->updateFile = sfConfig::get('sf_app_cache_dir') . '/myUpdateFile';
  }

  public function getText($pageName)
  {
    $filename = $pageName . '.' . sfConfig::get('app_wiki_format');
    //checkout
    $this->checkNew();
    $filepath = realpath($this->cacheDir . '/' . $filename);
    if(strncmp($filepath, $this->cacheDir, strlen($this->cacheDir)) !== 0) {
      return false;
    }
    return file_get_contents($filepath);
  }

  public static function convert($text)
  {
    $format = sfConfig::get('app_wiki_format');
    if ($format === 'hatena')
    {
      return HatenaSyntax::render($text, array('headerlevel' => 2));
    }
  }

  public function checkNew()
  {
    $this->checkout();
return;
    if(! file_exists($this->updateFile)) {
      $this->checkout();
      touch($this->updateFile);
      return;
    }
    // 
    if (filemtime($this->updateFile) <= time() - 60 * 60) {
      $this->checkout();
      touch($this->updateFile);
      return;
    }
  }



  public function checkout()
  {
    $repository = sfConfig::get('app_repos_path');
    $isNew = false;
    if(!file_exists($this->cacheDir . '/.git')) {
      $isNew = true;
    }
    if($isNew) {
      $git = new VersionControl_Git();
      $git->createClone($repository, false, $this->cacheDir);
      chmod($this->cacheDir, 0777);
    }else{
      $git = new VersionControl_Git($this->cacheDir);
      $git->getCommand('pull')->execute();
    }
  }

}
