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

/*
 * class RegistryItem
 * 
 * Data representation of an item in Registry
 * 
 * Notice that you never access it directly. Use Registry methods to manipulate items instead.
 */

class RegistryItem
{
   /*
    * public mixed $value
    * 
    * Value associated with name
    * 
    * Note that:
    *    - if item is persistent, and $isSynced is FALSE, $value contents may be not be real
    *    - name is case-insensitive
    */
    
   public $value;
    
   /*
    * public bool $isPersistent
    * 
    * Whether the item's value is saved to database
    * 
    * Note that if item is persistent, and $isSynced is FALSE, $value contents may be not be real
    */
     
   public $isPersistent;
   
   /*
    * public bool/string $isReadOnly;
    * 
    * [bool]:   Whether properties of an item are unchangeable
    * [string]: Item is private (access to item is permited only to class, which name is given)
    */
   
   public $isReadOnly;
   
   //--
   
   /*
    * public bool $isSynced
    * 
    * Whether item's value is synchronized with value in database (if persistent)
    */
   
   public $isSynced = false;
   
   //--
   
   public function __construct($value, $isPersistent, $isReadOnly)
   {
      $this->value        = $value;
      $this->isPersistent = $isPersistent;
      $this->isReadOnly   = $isReadOnly;
   }
}