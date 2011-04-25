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

// TODO: redesign it

class FrontendLibraries extends Extension
{
   public static function init()
   {
      // libraries versions
      
      $sh_version     = '3.0.83';
      
      $versions = $sh_version;
      
      // adding
      
      $baseURL = SystemURL . 'core/FrontendLibraries/';
      
      Watermelon::$headTags[] = '<link rel="stylesheet" href="' . $baseURL . 'sh.css.php?v=3.0.83">';
      
      Watermelon::$tailTags[] = '<script src="' . $baseURL . 'js.php?v=' . $versions . '"></script>';
      Watermelon::$tailTags[] = '<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.5.2/jquery.min.js"></script>';
   }
}