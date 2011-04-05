<?php
 //  
 //  This file is part of Watermelon
 //  
 //  Copyright 2010 Radosław Pietruszewski.
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
 * abstract class Skin
 * 
 * Skins base class
 */

abstract class Skin
{
   /*
    * Variables available in skin through simple ${name}:
    * 
    * string $content           - page content
    * string $pageTitle         - title of currently loaded page (page header)
    * string $siteName          - name of whole website
    * string $siteSlogan        - short description of website
    * string $footer            - text (HTML) to be placed in footer
    * bool   $noHeader - whether page title header should not be displayed in skin.
    *                             Allows to display header in other place to preserve coherence (e.g. when using <article>)
    */
   
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
    *    $menu = array(array($title, $blockset, $block, array $parameters), ...)
    *       $title      - header above block
    *       $blockset   - name of Blockset containing requested block
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
    * public void drawTextMenu(int $id)
    * 
    * Prints text menu number $id
    */
   
   public function drawTextMenu($id)
   {
      foreach($this->textMenus[$id] as $menuItem)
      {
         list($name, $URL, $relative, $title) = $menuItem;
         
         if($relative)
         {
            $URL = SiteURL($URL);
         }
         
         echo '<li><a href="' . $URL . '"' . (is_string($title) ? ' title="' . $title . '"' : '') . '>'. $name. '</a></li>';
      }
   }
   
   /*
    * public void drawHeadTags()
    * 
    * Prints tags from ->headTags
    */
   
   public function drawHeadTags()
   {
      foreach($this->headTags as $tag)
      {
         echo $tag . "\n";
      }
   }
   
   /*
    * public void drawTailTags()
    * 
    * Prints tags from ->tailTags
    */
   
   public function drawTailTags()
   {
      foreach($this->tailTags as $tag)
      {
         echo $tag . "\n";
      }
   }
   
   /*
    * public void drawMessages()
    * 
    * Prints messages
    */
   
   public function drawMessages()
   {
      foreach($this->messages as $message)
      {
         list($type, $messageString) = $message;
         
         echo '<div class="' . $type . 'Box">' . SiteLinks($messageString) . '</div>';
      }
   }
   
   public function __construct($variables, $skinPath)
   {
      $skin = new View($skinPath);
      
      foreach($variables as $key => &$value)
      {
         $skin->$key = $value;
         $this->$key = $value;
      }
      
      $skin->skin = $this;
      
      $skin->display();
   }
}