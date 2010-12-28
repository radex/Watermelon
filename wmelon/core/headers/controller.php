<?php
 //  
 //  This file is part of Watermelon CMS
 //  
 //  Copyright 2008-2010 Radosław Pietruszewski.
 //  
 //  Watermelon CMS is free software: you can redistribute it and/or modify
 //  it under the terms of the GNU General Public License as published by
 //  the Free Software Foundation, either version 3 of the License, or
 //  (at your option) any later version.
 //  
 //  Watermelon CMS is distributed in the hope that it will be useful,
 //  but WITHOUT ANY WARRANTY; without even the implied warranty of
 //  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 //  GNU General Public License for more details.
 //  
 //  You should have received a copy of the GNU General Public License
 //  along with Watermelon CMS. If not, see <http://www.gnu.org/licenses/>.
 //  

/*
 * abstract class Controller
 * 
 * Controllers base class
 */

abstract class Controller
{
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
    * public enum $outputType
    * 
    * Requested representation method of output:
    *    ::Skinned_OutputType - Typical page
    *    ::Plain_OutputType   - Only echoed data is outputed, without any skin around
    *    ::XML_OutputType     - XML created from structure in $output property
    */
   
   public $outputType = self::Skinned_OutputType;
   
   const Skinned_OutputType = 1;
   const Plain_OutputType   = 2;
   const XML_OutputType     = 3;
   
   /*
    * public SimpleXMLObject $output = null
    * 
    * Data to be outputed
    * 
    * Works only for XML output type (and possibly other future output types), for Skinned and Plain output types, just echo what you want
    * 
    * Note that it is by default null, so you have to initialize it with SimpleXMLObject for yourself
    */
   
   public $output;
   
   /*
    * public bool $dontShowPageTitle = false
    * 
    * Whether page title header should not be displayed in skin
    * 
    * Option allows to display header in other place to preserve coherence (e.g. when using <article>)
    */
   
   public $dontShowPageTitle = false;
   
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
    * public void addMessage(string $type, string $message)
    * 
    * Alias for Watermelon::addMessage()
    */
   
   public function addMessage($type, $message)
   {
      Watermelon::addMessage($type, $message);
   }
   
   /*
    * constructor
    */
   
   public function __construct()
   {
      $this->db       = new DB();
      $this->load     = new Loader();
      $this->registry = new Registry();
      
      // attempting to load model with the same name
      
      $className = substr(get_called_class(), 0, -11); // name of class - '_Controller'
      
      try
      {
         $this->model = Loader::model($className);
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
      
      // running skin, or outputing data
      
      if($this->outputType == self::Plain_OutputType)
      {
         echo $content;
         
         return;
      }
      elseif($this->outputType == self::XML_OutputType)
      {
         header('Content-Type: text/xml');
         
         echo $this->output->asXML();
         
         return;
      }
      
      // loading skin (output type is set to skinned)
      
      include WM_SkinPath . 'skin.php';
      
      if(Watermelon::$appType == Watermelon::Admin)
      {
         $className = 'ACPSkin';
      }
      else
      {
         $className = Watermelon::$config->skin . '_skin';
      }

      $skin = new $className;
      
      // head tags
      
      $headTags   = &Watermelon::$headTags;
      $headTags[] = &Watermelon::$config->headTags; 
      
      $siteName  = Watermelon::$config->siteName;
      $pageTitle = $this->pageTitle;
      
      if(Watermelon::$appType == Watermelon::Admin)
      {
         $title = empty($pageTitle) ? 'Panel Admina - ' . $siteName : $pageTitle . ' - Panel Admina - ' . $siteName;
      }
      elseif(Watermelon::$appType == Watermelon::Installer)
      {
         $title = 'Watermelon CMS';
      }
      else
      {
         $title = empty($pageTitle) ? $siteName : $pageTitle . ' - ' . $siteName;
      }
      
      $headTags[] = '<title>' . $title . '</title>';
      $headTags[] = '<script>Watermelon_baseURL = \'' . WM_SystemURL . '\'</script>';
      
      if(Watermelon::$appType == Watermelon::Site)
      {
         $headTags[] = '<link rel="alternate" type="application/atom+xml" href="' . WM_SiteURL . 'feed.atom"/>';
      }
      
      // tail tags
      
      $tailTags   = &Watermelon::$tailTags;
      $tailTags[] = &Watermelon::$config->tailTags;
      
      // setting configuration
      
      $skin->content    = &$content;
      
      $skin->pageTitle  = $pageTitle;
      $skin->dontShowPageTitle = $this->dontShowPageTitle;
      $skin->siteName   = htmlspecialchars($siteName);
      $skin->siteSlogan = htmlspecialchars(Watermelon::$config->siteSlogan);
      $skin->footer     = &Watermelon::$config->footer;
      
      $messages = $_SESSION['WM_Messages'];
      $_SESSION['WM_Messages'] = array();

      $skin->messages   = &$messages;
      $skin->headTags   = &$headTags;
      $skin->tailTags   = &$tailTags;
      $skin->blockMenus = &Watermelon::$config->blockMenus;
      $skin->textMenus  = &Watermelon::$config->textMenus;
      $skin->additionalData = $this->additionalData;

      $skin->display();
   }
}