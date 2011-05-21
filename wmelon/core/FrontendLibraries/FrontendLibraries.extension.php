<?php
 //  
 //  This file is part of Watermelon
 //  
 //  Copyright 2010-2011 Radosław Pietruszewski.
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

// TODO: redesign FrontendLibraries

class FrontendLibraries extends Extension
{
   public static function init()
   {
      $baseURL = SystemURL . 'core/FrontendLibraries/';
      
      Watermelon::$tailTags[] = '<style>@import url("' . $baseURL . 'sh.css.php?v=3.0.83");</style>';
      Watermelon::$tailTags[] = '<script src="' . $baseURL . 'sh.js.php?v=3.0.83"></script>';
      Watermelon::$tailTags[] = '<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.min.js"></script>';
      
      //TODO: jQuery fallback URL
   }
}