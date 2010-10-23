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
include 'TextFormInput.php';

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
    * public FormInput[] $inputs
    * 
    * Array of inputs
    * 
    * Avoid using it. Use ->addInput or ->addInputObject instead
    */
   
   public $inputs = array();
   
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
      $this->actionPage   = $actionPage;
      $this->fallbackPage = $fallbackPage;
   }
   
   /*
    * public void addInput(string $type, string $name, string $label[, bool $required = true[, array $args]])
    * 
    * Adds input to form
    * 
    * string $type     - type of input
    * string $name     - input name (identificator)
    * string $label    - description of the input
    * bool   $required - whether input is required
    * array  $args     - additional parameters of input
    */
   
   public function addInput($type, $name, $label, $required = true, array $args = array())
   {
      $className = $type . 'FormInput';
      
      $this->inputs[] = new $className($name, $label, $required, $args);
   }
   
   /*
    * public void addInputObject(FormInput $input)
    * 
    * Adds given input object to form
    */
   
   public function addInputObject(FormInput $input)
   {
      $this->inputs[] = $input;
   }
   
   /*
    * public string generate()
    * 
    * Generates actual HTML form (and returns it)
    */
   
   public function generate()
   {
      // storing form object in session (so that in can be reconstructed on action page)
      
      $_SESSION['Form_lastForm'] = serialize($this);
      
      // generating
      
      $r .= '<form action="' . SiteURI($this->actionPage) . '" method="post">' . "\n";
      
      foreach($this->inputs as $input)
      {
         $r .= $input->generate() . "\n";
      }
      
      $r .= '<label><span></span><input type="submit" value="' . $submitTitle . '"></label>';
      $r .= '</form>';
      
      return $r;
   }
   
   /*
    * public static array validate()
    * 
    * Validates submited form
    * 
    * If validation fails, browser will be automatically redirected back to the form
    * 
    * Returns: array('inputName' => 'inputValue', ...)
    */
   
   public static function validate()
   {
      $form = unserialize($_SESSION['Form_lastForm']);
      
      foreach($form->inputs as $input)
      {
         // getting value
         
         $input->value = $_POST[$input->name];
         
         // trimming
         
         if($input->trim)
         {
            $input->value = trim($input->value);
         }
         
         // required
         
         if($input->required && empty($input->value))
         {
            // field is required
         }
         
         // max length
         
         if(isset($input->maxLength) && strlen($input->value) > $input->maxLength)
         {
            // value is too long
         }
         
         // custom validation
         
         //$input->validate();
      }
      
      var_dump($form);
   }
}