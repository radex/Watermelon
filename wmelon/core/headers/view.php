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
 * class View
 * 
 * Class representing view
 * 
 * Notice that you don't create any View objects directly. You get them using View() function.
 */

include WM_Core . 'PHPTAL/PHPTAL.php';
include WM_Core . 'ViewPreFilter.php';

class View
{
   /*
    * public array $params
    * 
    * Parameters to be passed to the actual view
    */
   
   public $params = array();
   
   /*
    * private string $viewPath
    * 
    * Path to the view
    * 
    * It is set by Loader::view(), and then used by ->display()
    */
   
   private $viewPath;
   
   
   /**************************************************************************/
   
   /*
    * public void display()
    * 
    * Loads actual view and displays generated content
    * 
    * To return content and not display it, use ->generate() instead
    */
   
   public function display()
   {
      echo $this->generate();
   }
   
   /*
    * public string generate()
    * 
    * Loads actual view and returns generated content
    * 
    * Use ->display() to display it
    */
   
   public function generate()
   {
      // getting view file contents, and stripping from <?php die?\>
      
      $viewContent = file_get_contents($this->viewPath);
      $viewContent = str_replace('<?php die?>', '', $viewContent);
      $viewContent = '<tal:block>' . $viewContent . '</tal:block>';
      
      // PHPTAL configuration
      
      $view = new PHPTAL;
      $view->setSource($viewContent, $this->viewPath);
      
      foreach($this->params as $key => $value)
      {
         $view->set($key, $value);
      }
      
      $view->setOutputMode(PHPTAL::HTML5);
      
      // PHPTAL filters
      
      $view->addPreFilter(new ViewPreFilter);                  // <?  -->  <?php, <?=  -->  <?php echo
      $view->addPreFilter(new PHPTAL_PreFilter_StripComments);
      
      if(!defined('WM_Debug'))
      {
         $view->addPreFilter(new PHPTAL_PreFilter_Normalize);  // strips whitespaces etc.
      }
      
      // predefined parameters
      // (NOTE: when changed, change also array in ->__set())
      
      if(class_exists('Users'))      // there's no Users in Installer
      {
         $view->set('isAdmin', Users::adminPrivileges());
      }
      
      // executing
      
      return $view->execute();
   }
   
   /*
    * public View set(string $name, mixed $value)
    * 
    * Sets $name to $value, and returns the same object, so you can use it in chain, e.g:
    * 
    * $this->load->view('test')->set('foo', 'bar')->display();
    */
   
   public function set($name, $value)
   {
      $this->__set($name, $value);
      
      return $this;
   }
   
   /*
    * constructor
    */
   
   public function __construct($viewPath)
   {
      $this->viewPath = $viewPath;
   }
   
   /*
    * param getter
    */
   
   public function __get($name)
   {
      return $this->params[$name];
   }
   
   /*
    * param setter
    */
   
   public function __set($name, $value)
   {
      if(!in_array($name, array('isAdmin'))) // can't set predefined param
      {
         $this->params[$name] = $value;
      }
      else
      {
         throw new WM_Exception($name . ' jest zarezerwowaną nazwą parametru w widokach', 'view:predefinedParam');
      }
   }
}