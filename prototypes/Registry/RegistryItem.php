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
   /*
    * public mixed $value
    * 
    * Value associated with name
    * 
    * Note that $value is treated as default value if $isPersistent is set to TRUE, which means that if item doesn't exist in database yet, newly created one will have value equaling default value
    */
    
   public $value;
    
   /*
    * public bool $isPersistent
    * 
    * Whether the item's value is saved to database
    * 
    * Note that:
    *    - read-only item can be changed anyway if is also persistent
    *    - item can't be private and persistent at the same time
    *    - $value is treated as default value if $isPersistent is set to TRUE, which means that if item doesn't exist in database yet, newly created one will have value equaling default value
    */
     
   public $isPersistent;
   
   /*
    * public bool/string $isReadOnly;
    * 
    * [bool]:
    *    Whether properties of an item are unchangeable
    * 
    *    Note that read-only item can be changed anyway if is also persistent
    * 
    * [string]:
    *    Item is private (access to item is permited only to class, which name is given)
    * 
    *    Note that item can't be private and persistent at the same time
    */
   
   public $isReadOnly;
   
   //--
   
   public function __construct($value, $isPersistent, $isReadOnly)
   {
      $this->value        = $value;
      $this->isPersistent = $isPersistent;
      $this->isReadOnly   = $isReadOnly;
   }
}