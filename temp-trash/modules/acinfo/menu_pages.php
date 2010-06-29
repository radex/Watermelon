<?php
 //  
 //  This file is part of Watermelon CMS
 //  
 //  Copyright 2009 Radosław Pietruszewski.
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

class ACMenu_pages implements iACMenu
{
   public function getMenu($class, $method, $segments)
   {
      $header = 'Strony';
      
      if($class == 'pages')
      {
         $links[] = array('pages', 'lista');
         $links[] = array('pages/new', 'nowa');
      }
      
      return array($header, $links);
   }
}

?>