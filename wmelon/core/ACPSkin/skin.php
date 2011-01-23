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

class ACPSkin extends Skin
{
   /*
    * Array of items in left navigation bar in format as described in ACPInfo class
    */
   
   private $leftNavItems = array();
   
   /*
    * Is any page selected in primary navigation bar
    */
   
   private $isSelected = false;
   
   /*
    * Checks whether current page starts with $page identificator
    */
   
   private function startsWith($page)
   {
      $currentPage   = array();
      $currentPage[] = Watermelon::$controllerName;
      $currentPage[] = Watermelon::$actionName;
      $currentPage   = array_merge($currentPage, Watermelon::$segments);
      $currentPage   = implode('/', $currentPage);
      
      return (substr($currentPage, 0, strlen($page)) == $page);
   }
   
   /*
    * Left side of primary navigation bar
    */
   
   public function drawLeftNav()
   {
      foreach($this->leftNavItems as $i => $item)
      {
         list($name,,$title, $page) = $item;
         
         // title
         
         if(!empty($title))
         {
            $title = ' title="' . $title . '"';
         }
         
         // checking, whether it's current page
         
         $currentPage = '';
         
         if($this->startsWith($page))
         {
            $currentPage = ' class="currentPage"';
            
            $this->isSelected = true;
         }
         
         // generating
         
         echo '<li' . $currentPage . '><a href="' . SiteURL($page) . '"' . $title . '>' . $name . "</a>\n";
      }
      
      $this->startsWith('asdasd');
   }
   
   /*
    * Right side of primary navigation bar
    */
   
   public function drawRightNav()
   {
      if($this->startsWith('options'))
      {
         echo '<li class="currentPage"><a href="' . SiteURL('options') . '">Ustawienia</a>' . "\n";
         
         $this->isSelected = true;
      }
      else
      {
         echo '<li><a href="' . SiteURL('options') . '">Ustawienia</a>' . "\n";
      }
      
      echo '<li><a href="' . SiteURL('#/users/logout') . '">Wyloguj</a>' . "\n";
   }
   
   /*
    * Secondary navigation bar
    */
   
   public function drawSubNav()
   {
      // checking if any item is selected
      
      if(!$this->isSelected)
      {
         return;
      }
      
      // checking if any subitems are given
      
      $subitems = Watermelon::$controller->subNav;
      
      if(empty($subitems))
      {
         return;
      }
      
      // composing links array
      
      $links = array();
      
      foreach($subitems as $subitem)
      {
         list($name, $title, $page) = $subitem;
         
         // title
         
         if(!empty($title))
         {
            $title = ' title="' . $title . '"';
         }
         
         // checking, whether it's current page
         
         $currentPage = '';
         
         if($this->startsWith($page))
         {
            $currentPage = ' class="currentPage"';                    // FIXME: if two subitems are starting the same, both will be shown as current
         }
         
         // generating
         
         $links[] = '<a href="' . SiteURL($page) . '"' . $title . $currentPage . '>' . $name . '</a>';
      }
      
      // final generation
      
      echo '<nav id="subNav">';
      
      echo implode(' | ', $links);
      
      echo '</nav>';
   }
   
   /*
    * Loads ACP Info files
    */
   
   private function loadNavInfo()
   {
      $acpInfoFiles = Watermelon::$config->modulesList->acpinfofiles;
      
      foreach($acpInfoFiles as $bundle)
      {
         include WM_Bundles . $bundle . '/' . $bundle . '.acpinfo.php';
         
         $className  = $bundle . '_ACPInfo';
         $bundleInfo = new $className;
         $bundleInfo = $bundleInfo->info();
         
         // importing items to local array
         
         foreach($bundleInfo as $item)
         {
            // checking type
            
            if($item[1] == null)
            {
               $this->leftNavItems[] = $item;
            }
         }
      }
   }
   
   /*
    * display
    */
   
   public function __construct($variables, $skinPath)
   {
      $this->loadNavInfo();
      
      parent::__construct($variables, $skinPath);
   }
}