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

class FrontendLibraries_Extension extends Extension
{
   public static function onAutoload()
   {
      Watermelon::$headTags[] = '<link rel="stylesheet" href="' . WM_SystemURL . 'core/FrontendLibraries/sh.css.php?v=3.0.83">';
      
      Watermelon::$tailTags[] = '<script src="' . WM_SystemURL . 'core/FrontendLibraries/jquery.js?v=1.4.3"></script>';
      Watermelon::$tailTags[] = '<script src="' . WM_SystemURL . 'core/FrontendLibraries/sh.js.php?v=3.0.83"></script>';
   }
}