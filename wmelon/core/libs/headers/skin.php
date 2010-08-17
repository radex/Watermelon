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
   private $content;  // page content to generate. Value is defined by Watermelon class
   private $headData; // data (tags) to put in <head> section. -||-
   
   public function __construct($content, $headData)
   {
      $this->content  = $content;
      $this->headData = $headData;
   }
   
   public function display()
   {
      $headData = $this->headData;
      $content  = $this->content;
      
      include WM_SkinPath . 'index.php';
   }
}