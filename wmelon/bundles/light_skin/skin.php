<?php
 //  
 //  This file is part of Watermelon
 //  
 //  Copyright 2011 Radosław Pietruszewski.
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

class Light_Skin extends Skin
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
    * Displays content, changing <hx/> to <h(x+1)/>
    */
   
   public function drawContent()
   {
      $content = $this->content;
      
      $content = str_replace('<h3', '<h4', $content);
      $content = str_replace('<h2', '<h3', $content);
      $content = str_replace('<h1', '<h2', $content);
      $content = str_replace('</h3', '</h4', $content);
      $content = str_replace('</h2', '</h3', $content);
      $content = str_replace('</h1', '</h2', $content);
      
      echo $content;
   }
}