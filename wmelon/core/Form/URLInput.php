<?php
 //  
 //  This file is part of Watermelon
 //  
 //  Copyright 2011 Radosław Pietruszewski.
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
 * URL Input class
 */

class URLFormInput extends TextFormInput
{
   public function validate()
   {
      list($continue, $errors) = parent::validate();
      
      if(!$continue) return array(false, $errors);
      
      // if empty
      
      if(empty($this->value)) return array(true, $errors);
      
      // adding 'http://' if missing
      
      if(substr($this->value, 0, 7) != 'http://' && substr($this->value, 0, 8) != 'https://')
      {
         $this->value = 'http://' . $this->value;
      }
      
      // validating URL address
      
      if(filter_var($this->value, FILTER_VALIDATE_URL) === false)
      {
         $errors[] = 'Wartość pola "' . $this->label . '" nie jest poprawnym adresem URL';
      }
      
      return array(true, $errors);
   }
}