<?php
 //  
 //  This file is part of Watermelon
 //  
 //  Copyright 2010-2011 Radosław Pietruszewski.
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
 * Form helper class modified for Installer use
 */

class InstallerForm extends Form
{
   public $displaySubmitButton = false;
   public $firstInputAdded = false;
   
   /*
    * constructor
    */
   
   public function __construct($formID, $actionPage)
   {
      parent::__construct($formID, $actionPage);
   }
   
   /*
    * validating
    */
   
   public static function validate($formID)
   {
      return parent::validate($formID, '');
   }
   
   /*
    * adding inputs
    */
   
   public function addInput($type, $name, $label, $required = true, array $args = array())
   {
      // add '.firstInput' class to the first input
      // (needed because of some strange jQuery bug)
      
      return parent::addInput($type, $name, $label, $required, $args);
   }
}