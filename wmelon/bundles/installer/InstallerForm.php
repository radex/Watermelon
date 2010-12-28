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
 * Form helper class modified for Installer use
 */

class InstallerForm extends Form
{
   public $displaySubmitButton = false;
   
   /*
    * constructor
    * 
    * Only form ID is needed - the rest is determined automatically
    */
   
   public function __construct($formID)
   {
      // getting currentStep
      
      $currentStep = $_SESSION['currentStep'];
      $nextStep    = $currentStep + 1;
      
      // parent constructor
      
      parent::__construct($formID, (string) $nextStep, (string) $currentStep);
   }
   
   /*
    * generating
    */
   
   public function generate()
   {
      $generated = parent::generate();
      
      // getting <form ...> declaration
      
      $generated = preg_replace_callback('/<form([^>]+)>/', function($formOpen)
         {
            // passing <form> declaration to $additionalData (where it will be used by skin)
            
            Watermelon::$controller->additionalData->formOpen = $formOpen[0];
            
            return '';
         },
         $generated);
      
      // deleting </form>
      
      $generated = str_replace('</form>', '', $generated);
      
      // returning
      
      return $generated;
   }
}