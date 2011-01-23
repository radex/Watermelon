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

class WCMSLay_Skin extends Skin
{
   /*
    * text menus
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
         
         echo '<a href="' . $URL . '"' . (is_string($title) ? ' title="' . $title . '"' : '') . '>'. $name. '</a>';
      }
   }
   
   /*
    * block menus
    */
   
   protected function drawBlockMenu($id)
   {
      foreach($this->blockMenus[$id] as $menu)
      {
         echo '<section>';
         
         list($title, $blockSet, $block, $parameters) = $menu;
         
         echo '<h2>' . $title . '</h2>';
         
         $blockObj = BlockSet($blockSet);
         
         call_user_func_array(array($blockObj, $block), $parameters);
         
         echo '</section>';
      }
   }
}