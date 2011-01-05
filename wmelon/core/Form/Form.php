<?php
 //  
 //  This file is part of Watermelon CMS
 //  
 //  Copyright 2010-2011 Radosław Pietruszewski.
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
include 'TextInput.php';
include 'PasswordInput.php';
include 'EmailInput.php';
include 'URLInput.php';

include 'Textarea.php';

include 'HiddenInput.php';
include 'HiddenData.php';

// TODO: find a better solution to the bug with form in session (current fix sucks and doesn't fix the problem completely)

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
    * public bool $displaySubmitButton
    * 
    * Whether submit button should be automatically added to the form
    */
   
   public $displaySubmitButton = true;
   
   /*
    * public string $submitLabel
    * 
    * Label (value="") of submit button
    */
   
   public $submitLabel;
   
   /*
    * public array $extraFormAttributes
    * 
    * Non-standard attributes to be added to <form> in 'attribute' => 'value' formatted array
    */
   
   public $extraFormAttributes = array();
   
   /*
    * protected array $items
    * 
    * Array of items - items (FormInput objects) and HTML (strings)
    */
   
   protected $items = array();
   
   /*
    * protected array $errors
    * 
    * Array of errors to display
    */
   
   protected $errors = array();
   
   /*
    * protected bool $noAdding = false
    * 
    * Whether ->addInput/addInputObject/addHTML() calls should be ignored
    */
   
   protected $noAdding = false;
   
   /*
    * public void __construct(string $formID, string $actionPage, string $fallbackPage)
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
      
      $sessionName = 'Form_' . $formID;
      
      if(!isset($_SESSION[$sessionName]))
      {
         return;
      }
      
      $form = unserialize($_SESSION[$sessionName]);
      
      
      // check whether there are any errors
      
      if(empty($form->errors))
      {
         return;
      }
      
      // set items and errors on these from session, and disable adding inputs
      
      $this->items    = $form->items;
      $this->errors   = $form->errors;
      $this->noAdding = true;
      
      unset($_SESSION[$sessionName]);
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
      if($this->noAdding)
      {
         return;
      }
      
      $className = $type . 'FormInput';
      
      $this->items[$name] = new $className($name, $label, $required, $args);
   }
   
   /*
    * public void addInputObject(FormInput $input)
    * 
    * Adds given input object to form
    */
   
   public function addInputObject(FormInput $input)
   {
      if($this->noAdding)
      {
         return;
      }
      
      $this->items[$name] = $input;
   }
   
   /*
    * public void addHTML(string $html)
    * 
    * Adds $html to the form
    */
   
   public function addHTML($html)
   {
      if($this->noAdding)
      {
         return;
      }
      
      $this->items[] = $html;
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
      
      $_SESSION['Form_' . $this->formID] = serialize($this);
      
      // extra <form> attributes
      
      $attributes = '';
      
      foreach($this->extraFormAttributes as $attribute => $value)
      {
         $attributes .= ' ' . $attribute . '="' . $value . '"';
      }
      
      // generating
      
      $r .= '<form action="' . SiteURL($this->actionPage) . '" method="post"' . $attributes . ">\n";
      $r .= '<input type="hidden" name="formID" value="' . $this->formID . '">' . "\n";
      
      // items
      
      foreach($this->items as $item)
      {
         if(is_object($item))
         {
            $r .= $item->generate() . "\n";
         }
         else
         {
            $r .= $item . "\n";
         }
      }
      
      // submit button
      
      if($this->displaySubmitButton)
      {
         if(empty($this->submitLabel))
         {
            $submitLabel = ' value="Zapisz"';
         }
         else
         {
            $submitLabel = ' value="' . $this->submitLabel . '"';
         }
      
         $r .= '<label><span></span><input type="submit"' . $submitLabel . '></label>';
      }
      
      //--
      
      $r .= '</form>';
      
      return $r;
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
      
      $sessionName = 'Form_' . $formID;
      
      if(!isset($_SESSION[$sessionName]))
      {
         SiteRedirect($fallbackPage);
         return;
      }
      
      $form = unserialize($_SESSION[$sessionName]); 
      
      // validating form
      
      foreach($form->items as $input)
      {
         // strings (HTML) are ignored
         
         if(!is_object($input))
         {
            continue;
         }
         
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
         $_SESSION[$sessionName] = serialize($form);
         SiteRedirect($fallbackPage);
      }
      
      // if everything is fine, returning form object
      
      unset($_SESSION[$sessionName]);
      
      return $form;
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
      $_SESSION['Form_' . $this->formID] = serialize($this);
      
      SiteRedirect($this->fallbackPage);
   }
   
   /*
    * public string get(string $inputName)
    * 
    * Returns value of input with given name
    */
   
   public function get($name)
   {
      return $this->items[$name]->value;
   }
   
   /*
    * public object getAll()
    * 
    * Returns values of all inputs in form as object
    */
   
   public function getAll()
   {
      $values = new stdClass;
      
      foreach($this->items as $name => $input)
      {
         if(is_object($input))
         {
            $values->$name = $input->value;
         }
      }
      
      return $values;
   }
}