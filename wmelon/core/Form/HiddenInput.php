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

/*
 * Hidden Input class
 */

class HiddenFormInput extends FormInput
{
   /*
    * public void __construct(string $name, string $value[, false, array $args]])
    * 
    * Constructor
    * 
    * string $name     - input name (identificator)
    * string $value    - value of input (note that in all other inputs label is here instead of value)
    * false            - N/A
    * array  $args     - additional parameters of input
    */
   
   public function __construct($name, $value, $r = false, array $args = array())
   {
      $this->name     = $name;
      $this->value    = $value;
      $this->required = false;
      
      foreach($args as $key => $value)
      {
         $this->$key = $value;
      }
   }
   
   /*
    * Generating actual HTML
    */
   
   public function generate($inside = '')
   {
      return '<input type="hidden" name="' . $this->name . '" value="' . htmlspecialchars($this->value) . '">';
   }
   
   /*
    * public array validate()
    * 
    * No validation
    */
   
   public function validate()
   {
      return array(true, array());
   }
}