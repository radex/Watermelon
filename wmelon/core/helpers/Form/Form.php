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
 * Form generator
 */

include 'FormInput.php';

class Form
{
   /*
    * public string $actionPage
    * 
    * Name of page (on the same website) where form validity will be checked
    */
   
   public $actionPage;
   
   /*
    * public string $fallbackPage
    * 
    * Name of page (on the same website) where form will be displayed (where errors will be shown)
    */
   
   public $fallbackPage;
   
   /*
    * public bool $globalMessages = true
    * 
    * Whether to show errors on top of a page (using standard messages; if true) or above form (if false)
    */
   
   public $globalMessages = true;
   
   /*
    * public string $submitTitle
    * 
    * Title (value="") of submit button
    */
   
   public $submitTitle;
   
   /*
    * array of inputs
    */
   
   private $inputs = array();
   
   /*
    * public void __construct(string $action, string $fallbackPage)
    * 
    * Constructor
    * 
    * string $actionPage   - page where form validity will be checked
    * string $fallbackPage - page where form will be displayed (where errors will be shown)
    * 
    * Page - name of page on the same website (e.g. foo/bar/)
    */
   
   public function __construct($actionPage, $fallbackPage)
   {
      //TODO
   }
   
   /*
    * public void addInput(string $type, string $name[, bool $required = true[, array $args]])
    * 
    * Adds input to form
    * 
    * string $type - type of input (TODO)
    * string $name - input name (identificator)
    * 
    * TODO
    */
   
   public function addInput($type, $name, $required = true, $args = array())
   {
      
   }
   
   /*
    * public void addInputObject(FormInput $input)
    * 
    * Adds given input object to form
    */
   
   public function addInputObject(FormInput $input)
   {
      
   }
   
   /*
    * public string generate()
    * 
    * Generates actual HTML form (and returns it)
    */
   
   public function generate()
   {
      
   }
}