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
 * Input abstract class
 */

abstract class FormInput
{
   /*
    * public string $name
    * 
    * Name (identificator, name="") of the input
    */
   
   public $name;
   
   /*
    * public string $label
    * 
    * Description of the input
    */
   
   public $label;
   
   /*
    * public bool $required = true
    * 
    * Whether input is required
    */
   
   public $required = true;
   
   /*
    * public string $value (optional)
    * 
    * Default value of the input
    */
   
   /*
    * public string $maxLength (optional)
    * 
    * Maximum length of input value
    */
   
   /*
    * public bool $trim = true
    * 
    * Whether input value should be trim()ed
    */
   
   public $trim = true;
   
   /*
    * public void __construct(string $name, string $label[, bool $required = true[, array $args]])
    * 
    * Constructor
    * 
    * string $name     - input name (identificator)
    * string $label    - description of the input
    * bool   $required - whether input is required
    * array  $args     - additional parameters of input
    */
   
   public function __construct($name, $label, $required = true, array $args = array())
   {
      $this->name     = $name;
      $this->label    = $label;
      $this->required = $required;
      
      foreach($args as $key => $value)
      {
         $this->$key = $value;
      }
   }
   
   /*
    * public string generate()
    * 
    * Generates actual input HTML
    * 
    * Don't call it directly - add input objects to form and generate the form instead
    */
   
   public function generate($inside = '')
   {
      return '<label><span>' . $this->label . '</span>' . $inside . '</label>';
   }
}