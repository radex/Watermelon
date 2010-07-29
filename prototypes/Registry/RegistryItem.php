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

class RegistryItem
{
   public $value;       // [mixed]
   public $isReadOnly; // [bool] - whether properties of an item are unchangeable
   // public $isTransient; // [bool/string] - if TRUE, you'll be able to access an item only once, and then it will be invalidated. String value works the same as TRUE, with the difference, that the access will be permited only to class, which name is given. Note that transient properties are also automatically immutable
   
   public function __construct($value, $isReadOnly/*, $isTransient*/)
   {
      /*if($isTransient !== false)
      {
         $isReadOnly = true;
      }*/
      
      $this->value       = $value;
      $this->isReadOnly = $isReadOnly;
      // $this->isTransient = $isTransient;
   }
}