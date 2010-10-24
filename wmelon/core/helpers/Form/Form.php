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
include 'PasswordFormInput.php';
include 'EmailFormInput.php';
include 'TextareaFormInput.php';

class Form
{
   /*
    * public string $formID
    * 
    * Unique name of the form, preferably in "author.bundle.form" format
    */
   
   public $formID;
   
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
    * public string $submitLabel
    * 
    * Label (value="") of submit button
    */
   
   public $submitLabel;
   
   /*
    * private FormInput[] $inputs
    * 
    * Array of inputs
    */
   
   private $inputs = array();
   
   /*
    * private array $errors
    * 
    * Array of errors to display
    */
   
   private $errors = array();
   
   /*
    * private bool $noInputs = false
    * 
    * Whether ->addInput/addInputObject() calls should be ignored
    */
   
   private $noInputs = false;
   
   /*
    * public void __construct(string $formID, string $action, string $fallbackPage)
    * 
    * Constructor
    * 
    * string $formID       - unique name of the form, preferably in "author.bundle.form" format
    * string $actionPage   - page where form validity will be checked
    * string $fallbackPage - page where form will be displayed (where errors will be shown)
    * 
    * Page - name of page on the same website (e.g. foo/bar/)
    */
   
   public function __construct($formID, $actionPage, $fallbackPage)
   {
      $this->formID       = $formID;
      $this->actionPage   = $actionPage;
      $this->fallbackPage = $fallbackPage;
      
      // check whether form exists in session
      
      if(!isset($_SESSION['Form_lastForm']))
      {
         return;
      }
      
      $form = unserialize($_SESSION['Form_lastForm']);
      
      // check whether form in session is the same as this one
      // and if there are any errors
      
      if($form->formID != $formID || empty($form->errors))
      {
         return;
      }
      
      // set inputs and errors on these from session, and disable adding inputs
      
      $this->inputs   = $form->inputs;
      $this->errors   = $form->errors;
      $this->noInputs = true;
      
      unset($_SESSION['Form_lastForm']);
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
      if($this->noInputs)
      {
         return;
      }
      
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
      if($this->noInputs)
      {
         return;
      }
      
      $this->inputs[] = $input;
   }
   
   /*
    * public string generate()
    * 
    * Generates actual HTML form (and returns it)
    */
   
   public function generate()
   {
      // displaying errors (if any)
      
      foreach($this->errors as $error)
      {
         if($this->globalMessages)
         {
            Watermelon::addMessage('error', $error);
         }
         else
         {
            $r .= '<div class="errorBox">' . $error . '</div>';
         }
      }
      
      $this->errors = array();
      
      // storing form object in session (so that in can be reconstructed on action page)
      
      $_SESSION['Form_lastForm'] = serialize($this);
      
      // generating
      
      $r .= '<form action="' . SiteURI($this->actionPage) . '" method="post">' . "\n";
      $r .= '<input type="hidden" name="formID" value="' . $this->formID . '">';
      
      foreach($this->inputs as $input)
      {
         $r .= $input->generate() . "\n";
      }
      
      if(!empty($this->submitLabel))
      {
         $submitLabel = ' value="' . $this->submitLabel . '"';
      }
      
      $r .= '<label><span></span><input type="submit"' . $submitLabel . '></label>';
      $r .= '</form>';
      
      return $r;
   }
   
   /*
    * public void addError(string $errorMessage)
    * 
    * Adds error to be displayed
    */
   
   public function addError($errorMessage)
   {
      $this->errors[] = $errorMessage;
   }
   
   /*
    * public void fallBack()
    * 
    * Redirects browser back to form to show errors
    */
   
   public function fallBack()
   {
      $_SESSION['Form_lastForm'] = serialize($this);
      
      SiteRedirect($this->fallbackPage);
   }
   
   /*
    * public static Form validate(string $formID, string $fallbackPage)
    * 
    * Validates submited form
    * 
    * If validation fails, browser will be automatically redirected back to the form
    * 
    * Returns form object
    * 
    * string $formID       - identificator of form to be validated
    * string $fallbackPage - page where form was displayed (in case of errors like form doesn't exist in session)
    */
   
   public static function validate($formID, $fallbackPage)
   {
      // checking if form exists
      
      if(!isset($_SESSION['Form_lastForm']))
      {
         SiteRedirect($fallbackPage);
         return;
      }
      
      $form = unserialize($_SESSION['Form_lastForm']);
      
      // checking if form in session is the same form we're supposed to validate
      
      if($formID != $form->formID)
      {
         unset($_SESSION['Form_lastForm']);
         SiteRedirect($fallbackPage);
         return;
      } 
      
      // validating form
      
      foreach($form->inputs as $input)
      {
         // getting value
         
         $input->value = $_POST[$input->name];
         
         // custom validation
         
         list(,$inputErrors) = $input->validate();
         
         foreach($inputErrors as $error)
         {
            $form->errors[] = $error;
         }
      }
      
      // if errors, get back to form to display them
      
      if(!empty($form->errors))
      {
         $_SESSION['Form_lastForm'] = serialize($form);
         SiteRedirect($fallbackPage);
      }
      
      // if everything is fine, returning form object
      
      unset($_SESSION['Form_lastForm']);
      
      return $form;
   }
}