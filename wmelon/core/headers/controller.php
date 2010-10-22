<?php
 //  
 //  This file is part of Watermelon CMS
 //  
 //  Copyright 2008-2010 Radosław Pietruszewski.
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
 * abstract class Controller
 * 
 * Controllers base class
 */

abstract class Controller
{
   /*
    * public string $pageTitle
    * 
    * Header of page
    */
   
   public $pageTitle;
   
   /*
    * public array $additionalData
    * 
    * Non-standard data to be passed to skin
    * 
    * Useful in making custom apps
    */
   
   public $additionalData = array();
   
   /*
    * public enum $outputType
    * 
    * Requested representation method of output:
    *    ::Skinned_OutputType - Typical page
    *    ::Plain_OutputType   - Only echoed data is outputed, without any skin around
    *    ::XML_OutputType     - XML created from structure in $output property
    */
   
   public $outputType = self::Skinned_OutputType;
   
   const Skinned_OutputType = 1;
   const Plain_OutputType   = 2;
   const XML_OutputType     = 3;
   
   /*
    * public SimpleXMLObject $output = null
    * 
    * Data to be outputed
    * 
    * Works only for XML output type (and possibly other future output types), for Skinned and Plain output types, just echo what you want
    * 
    * Note that it is by default null, so you have to initialize it with SimpleXMLObject for yourself
    */
   
   public $output;
   
   /*
    * public bool $dontShowPageTitle = false
    * 
    * Whether page title header should not be displayed in skin
    * 
    * Option allows to display header in other place to preserve coherence (e.g. when using <article>)
    */
   
   public $dontShowPageTitle = false;
   
   //--
   
   public function __construct()
   {
      $this->db       = new DB();
      $this->load     = new Loader();
      $this->registry = new Registry();
      
      // attempting to load model with the same name
      
      $className = substr(get_called_class(), 0, -11); // name of class - '_Controller'
      
      try
      {
         $this->model = Loader::model($className);
      }
      catch(WMException $e){}
   }
}