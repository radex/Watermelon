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

abstract class Skin
{
   /*
    * public string $content
    * 
    * Page content
    */
   
   public $content;
   
   /*
    * public string $headTags
    * 
    * Array of tags to put in <head> section
    */
   
   public $headTags;
   
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
   
   public $blockMenus;
   
   /*
    * public array $textMenus
    * 
    * Array of text-based menus
    * 
    * $textMenus = array($menu, $menu, ...)
    *    $menu = array(array($name, $URI, $title), ...)
    *       $name  - name of link
    *       $URI   - URI links points to
    *       $title - description of link, shown when hovered (or NULL if none)
    */
   
   public $textMenus;
   
   /*
    * protected void drawTextMenu(int $id)
    * 
    * Prints text menu number $id
    */
   
   protected function drawTextMenu($id)
   {
      foreach($this->textMenus[$id] as $menuItem)
      {
         echo '<li>';
         echo '<a href="' . $menuItem[1] . '"' . (is_string($menuItem[2]) ? ' title="' . $menuItem[2] . '"' : '') . '>';
         echo $menuItem[0];
         echo '</a></li>';
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
    * public void display()
    * 
    * Displays skin
    * 
    * (Watermelon calls it for you)
    */
   
   public function display()
   {   
      $content    = &$this->content;
      $headTags   = &$this->headTags;
      $pageTitle  = &$this->pageTitle;
      $siteName   = &$this->siteName;
      $siteSlogan = &$this->siteSlogan;
      $footer     = &$this->footer;
      $blockMenus = &$this->blockMenus;
      $textMenus  = &$this->textMenus;
      
      include WM_SkinPath . 'index.php';
   }
}