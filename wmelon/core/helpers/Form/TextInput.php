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
 * Text Input class
 */

class TextFormInput extends FormInput
{
   /*
    * public string $inputType
    * 
    * type="" of input
    */
   
   public $inputType = 'text';
   
   /*
    * generating
    */
   
   public function generate()
   {
      $code = '<input type="' . $this->inputType . '"';
      
      // adding attributes
      
      foreach($this as $key => $value)
      {
         // some object properties shouldn't be added as HTML attributes
         
         $dont = array('trim', 'inputType', 'label', 'labelNote');
         
         if(in_array($key, $dont))
         {
            continue;
         }
         
         // value
         
         if($key == 'value')
         {
            $value = htmlspecialchars($value);
         }
         
         // appending attribute
         
         $key = strtolower($key);
         
         if(is_string($value) || is_int($value) || is_float($value))
         {
            $code .= ' ' . $key . '="' . $value . '"';
         }
         elseif($value === true)
         {
            $code .= ' ' . $key;
         }
      }
      
      return parent::generate($code . '>');
   }
}