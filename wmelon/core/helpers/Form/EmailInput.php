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
 * Email Input class
 */

class EmailFormInput extends TextFormInput
{
   public $inputType = 'email';
   
   public function validate()
   {
      list($continue, $errors) = parent::validate();
      
      if(!$continue) return array(false, $errors);
      
      // validating email address
      
      if(!ValidEmail($this->value) && $this->required)
      {
         $errors[] = 'Wartość pola "' . $this->label . '" nie jest poprawnym adresem email';
      }
      
      return array(true, $errors);
   }
}