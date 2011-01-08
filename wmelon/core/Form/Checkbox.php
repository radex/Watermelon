<?php
 //  
 //  This file is part of Watermelon CMS
 //  
 //  Copyright 2011 Radosław Pietruszewski.
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
 * Checkbox class
 */

class CheckboxFormInput extends FormInput
{
   /*
    * public void __construct(string $name, string $label[, false, array $args])
    * 
    * false - not applicable
    * 
    * other args - the same as in FormInput::__construct()
    */
   
   /*
    * generate
    */
   
   public function generate($inside = '')
   {
      $checkbox = '<input type="checkbox"';
      
      // adding attributes
      
      foreach($this as $key => $value)
      {
         // some object properties shouldn't be added as HTML attributes
         
         $dont = array('trim', 'label', 'labelNote', 'required');
         
         if(in_array($key, $dont))
         {
            continue;
         }
         
         // value
         
         if($key == 'value' && $value)
         {
            $checkbox .= ' checked';
            continue;
         }
         
         // appending attribute
         
         $key = strtolower($key);
         
         if(is_string($value) || is_int($value) || is_float($value))
         {
            $checkbox .= ' ' . $key . '="' . $value . '"';
         }
         elseif($value === true)
         {
            $checkbox .= ' ' . $key;
         }
      }
      
      $checkbox .= '>';
      
      // label note
      
      if(!empty($this->labelNote))
      {
         $labelNote = ' <small>' . $this->labelNote . '</small>';
      }
      
      return "<label>\n<span></span>\n" . $checkbox . $this->label . $labelNote . "</label>\n";
   }
   
   /*
    * validate
    */
   
   public function validate()
   {
      $this->value = isset($this->value);
      
      return array(true, array());
   }
}