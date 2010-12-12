<?php
 //  
 //  This file is part of Watermelon CMS
 //  
 //  Copyright 2010 Radosław Pietruszewski.
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
 * abstract class Skin
 * 
 * Skins base class
 */

abstract class Skin
{
   /*
    * public string $content
    * 
    * Page content
    */
   
   public $content;
   
   /*
    * public string $pageTitle
    * 
    * Title of currently loaded page (page header)
    */
   
   public $pageTitle;
   
   /*
    * public string $siteName
    * 
    * Name of whole website
    */
   
   public $siteName;
   
   /*
    * public string $siteSlogan
    * 
    * Slogan (some text, usually placed below site name) of website
    */
   
   public $siteSlogan;
   
   /*
    * public string $footer
    * 
    * Text to place in footer
    */
   
   public $footer;
   
   /*
    * public bool $dontShowPageTitle
    * 
    * Whether page title header should not be displayed in skin
    * 
    * Option allows to display header in other place to preserve coherence (e.g. when using <article>)
    */
   
   public $dontShowPageTitle;
   
   /*
    * public string[] $headTags
    * 
    * Array of tags to put before actual page content
    */
   
   public $headTags = array();
   
   /*
    * public string[] $tailTags
    * 
    * Array of tags to put after actual page content
    */
   
   public $tailTags = array();
   
   /*
    * public array $blockMenus
    * 
    * Array of block-based menus
    * 
    * $blockMenus = array($menu, $menu, ...)
    *    $menu = array(array($title, $blockSet, $block, array $parameters), ...)
    *       $title      - header above block
    *       $blockSet   - name of BlockSet containing requested block
    *       $block      - name of block to be called
    *       $parameters - arguments to be passed to block
    */
   
   public $blockMenus = array();
   
   /*
    * public array $textMenus
    * 
    * Array of text-based menus
    * 
    * $textMenus = array($menu, $menu, ...)
    *    $menu = array(array(string $name, string $URL, bool $relative, string $title), ...)
    *       string $name  - name of link
    *       string $URL   - name of the page on the same website (if $relative == TRUE) or absolute URL (if $relative == FALSE)
    *       string $title - description of link, shown when hovered (or NULL if none)
    */
   
   public $textMenus = array();
   
   /*
    * public array $messages
    * 
    * Array of messages (errors, warnings, etc.) to be shown
    * 
    * $messages = array($message, ...)
    *    $message = array(string $type, string $messageString)
    *       string $type - type of message (error, warning, info, tip, tick)
    *       string $messageString - actual message
    */
   
   public $messages = array();
   
   /*
    * public object $additionalData
    * 
    * Other data to be passed to skin
    * 
    * Useful in making custom apps
    */
   
   public $additionalData;
   
   /*
    * protected void drawTextMenu(int $id)
    * 
    * Prints text menu number $id
    */
   
   protected function drawTextMenu($id)
   {
      foreach($this->textMenus[$id] as $menuItem)
      {
         list($name, $URL, $relative, $title) = $menuItem;
         
         if($relative)
         {
            $URL = SiteURL($URL);
         }
         
         echo '<li>';
         echo '<a href="' . $URL . '"' . (is_string($title) ? ' title="' . $title . '"' : '') . '>'. $name. '</a>';
         echo '</li>';
      }
   }
   
   /*
    * protected void drawHeadTags()
    * 
    * Prints tags from ->headTags
    */
   
   protected function drawHeadTags()
   {
      foreach($this->headTags as $tag)
      {
         echo $tag . "\n";
      }
   }
   
   /*
    * protected void drawTailTags()
    * 
    * Prints tags from ->tailTags
    */
   
   protected function drawTailTags()
   {
      foreach($this->tailTags as $tag)
      {
         echo $tag . "\n";
      }
   }
   
   /*
    * protected void drawMessages()
    * 
    * Prints messages
    */
   
   protected function drawMessages()
   {
      foreach($this->messages as $message)
      {
         list($type, $messageString) = $message;
         
         echo '<div class="' . $type . 'Box">' . $messageString . '</div>';
      }
   }
   
   /*
    * public void display()
    * 
    * Displays skin
    * 
    * (Watermelon calls it for you)
    */
   
   public function display()
   {
      $content    = &$this->content;
      $pageTitle  = &$this->pageTitle;
      $siteName   = &$this->siteName;
      $siteSlogan = &$this->siteSlogan;
      $footer     = &$this->footer;
      $dontShowPageTitle = $this->dontShowPageTitle;
      
      $additionalData = &$this->additionalData;
      
      include WM_SkinPath . 'index.php';
   }
}