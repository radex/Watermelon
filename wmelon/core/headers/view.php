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
 * class View
 * 
 * Class representing view
 * 
 * Notice that you don't create any View objects. You get them from Loader::view(), or its shortcut view().
 */

class View
{
   
   /*
    * public array $parametrs
    * 
    * Parameters to be passed to the actual view
    */
   
   public $parameters = array();
   
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
    * public void display([$return = false])
    * 
    * Loads actual view
    * 
    * If $return is TRUE, output is returned instead of being displayed
    */
   
   public function display($return = false)
   {
      // getting view file contents, and stripping from <?die?\>
      
      $viewContent = file_get_contents($this->viewPath);
      $viewContent = str_replace('<?php die?>', '', $viewContent);
      $viewContent = '<tal:block>' . $viewContent . '</tal:block>';
      
      // PHPTAL configuration
      
      $view = new PHPTAL;
      $view->setSource($viewContent, $this->viewPath);
      
      foreach($this->parameters as $key => $value)
      {
         $view->set($key, $value);
      }
      
      $view->setOutputMode(PHPTAL::HTML5);
      $view->addPreFilter(new ViewPreFilter);
      $view->addPreFilter(new PHPTAL_PreFilter_StripComments);
      
      // returning or displaying
      
      if($return)
      {
         return $view->execute();
      }
      else
      {
         echo $view->execute();
      }
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
      $this->parameters[$name] = $value;
      
      return $this;
   }
   
   public function __construct($viewPath)
   {
      $this->viewPath = $viewPath;
   }
   
   public function __get($name)
   {
      return $this->parameters[$name];
   }
   
   public function __set($name, $value)
   {
      $this->parameters[$name] = $value;
   }
}