<?php
 //  
 //  This file is part of Watermelon
 //  
 //  Copyright 2008-2011 Radosław Pietruszewski.
 //  
 //  Watermelon is free software: you can redistribute it and/or modify
 //  it under the terms of the GNU General Public License as published by
 //  the Free Software Foundation, either version 3 of the License, or
 //  (at your option) any later version.
 //  
 //  Watermelon is distributed in the hope that it will be useful,
 //  but WITHOUT ANY WARRANTY; without even the implied warranty of
 //  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 //  GNU General Public License for more details.
 //  
 //  You should have received a copy of the GNU General Public License
 //  along with Watermelon. If not, see <http://www.gnu.org/licenses/>.
 //  

/*
 * abstract class Controller
 * 
 * Controllers base class
 */

abstract class Controller
{
   /*
    * public static string $bundleName
    * 
    * Name of bundle currently this controller belongs to
    * 
    * Don't change - it is set automatically
    */
   
   public $bundleName;
   
   /**************************************************************************/
   
   /*
    * public string $pageTitle
    * 
    * Header of page
    */
   
   public $pageTitle;
   
   /*
    * public object $additionalData
    * 
    * Non-standard data to be passed to skin
    * 
    * Useful in making custom apps
    */
   
   public $additionalData;
   
   /*
    * public string[] $segments
    * 
    * Array of resource name segments, stripped from controller and action name
    */
   
   public $segments;
   
   /*
    * public object $parameters
    * 
    * Object with parameters passed through URI, e.g. 'foo:bar' is ->foo = 'bar'
    */
   
   public $parameters;
   
   /*
    * public bool $plainOutput = false
    * 
    * If true, only echoed contents will be output (without skin and everything else)
    */
   
   public $plainOutput = false;
   
   /*
    * public bool $noHeader = false
    * 
    * Whether page title header should not be displayed in skin
    * 
    * Option allows to display header in other place by hand to preserve coherence (e.g. when using <article>)
    */
   
   public $noHeader = false;
   
   /*
    * public array $acpSubNav
    * 
    * Array of links in secondary navigation bar
    * 
    * Used only in ACP
    * 
    * Note that it is displayed only if controller is marked as selected in primary navigation bar
    * 
    * $acpSubNav = array($item, ...)
    * 
    * $item = array
    *    (
    *       string $name  - name of item (controller), displayed in main navigation bar
    *       string $title - (optional, leave null for no title) title displayed when link is hovered
    *       string $page  - base page of action, e.g. 'blog/new'
    *    )
    */
   
   public $acpSubNav;
   
   /*
    * public void displayError/displayNotice/displaySuccessNotice(string $message)
    * 
    * Shortcut for Watermelon::displayX
    */
   
   public function displayError($message)
   {
      Watermelon::displayError($message);
   }
   
   public function displayNotice($message)
   {
      Watermelon::displayNotice($message);
   }
   
   public function displaySuccessNotice($message)
   {
      Watermelon::displaySuccessNotice($message);
   }
   
   /*
    * constructor
    */
   
   public function __construct()
   {
      // attempting to load model with the same name
      
      $modelClassName = str_replace('_Controller', '_Model', get_called_class());
      
      try
      {
         $this->model = new $modelClassName;
      }
      catch(WMException $e){}
      
      // binding ->segments/parameters to Watermelon::*
      
      $this->segments   = &Watermelon::$segments;
      $this->parameters = &Watermelon::$parameters;
   }
   
   /*
    * generating
    */
   
   public function generate()
   {
      $content = ob_get_clean();
      
      $content = SiteLinks($content);
      
      // if plain, outputting data
      
      if($this->plainOutput)
      {
         echo $content;
         
         return;
      }
      
      // otherwise - if output type is set to skinned
      
      $config = &Watermelon::$config;
      
      // head tags
      
      $headTags = &Watermelon::$headTags;
      
      $siteName  = Watermelon::$config->siteName;
      $pageTitle = $this->pageTitle;
      
      if(Watermelon::$appType == Watermelon::Admin)
      {
         $title = empty($pageTitle) ? 'Panel Admina - ' . $siteName : $pageTitle . ' - Panel Admina - ' . $siteName;
      }
      elseif(Watermelon::$appType == Watermelon::Installer)
      {
         $title = 'Watermelon';
      }
      else
      {
         $title = empty($pageTitle) ? $siteName : $pageTitle . ' - ' . $siteName;
      }
      
      $headTags[] = '<title>' . $title . '</title>';
      $headTags[] = '<script>WM_SystemURL = \'' . WM_SystemURL . '\'</script>';
      $headTags[] = '<link rel="top" href="' . WM_SiteURL . '">';
      
      if(Watermelon::$appType == Watermelon::Site)
      {
         $headTags[] = '<link rel="alternate" type="application/atom+xml" href="' . WM_SiteURL . 'feed.atom"/>';
      }
      
      $headTags[] = $config->headTags;
      
      // tail tags
      
      $tailTags[] = $config->tailTags;
      $tailTags   = &Watermelon::$tailTags;
      
      // loading skin
      
      include WM_SkinPath . 'skin.php';
      
      if(Watermelon::$appType == Watermelon::Admin)
      {
         $className = 'ACPSkin';
      }
      else
      {
         $className = $config->skin . '_skin';
      }
      
      // setting configuration
      
      $messages = $_SESSION['wmelon.messages'];
      $_SESSION['wmelon.messages'] = array();
      
      $skin->content           = &$content;
      $skin->pageTitle         = $pageTitle;
      $skin->noHeader          = $this->noHeader;
      $skin->siteName          = htmlspecialchars($siteName);
      $skin->siteSlogan        = htmlspecialchars($config->siteSlogan);
      $skin->footer            = SiteLinks($config->footer);
      $skin->messages          = &$messages;
      $skin->headTags          = &$headTags;
      $skin->tailTags          = &$tailTags;
      $skin->blockMenus        = &$config->blockMenus;
      $skin->textMenus         = &$config->textMenus;
      $skin->additionalData    = $this->additionalData;

      new $className($skin, WM_SkinPath . 'index.php');
   }
}