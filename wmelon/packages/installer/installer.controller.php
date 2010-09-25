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

class Installer_Controller extends Controller
{
   /*
    * Main method - setting some constants, and running proper method
    */
   
   public function installer()
   {
      // TODO: Determine site, and system URL-s by getting URI, and checking whether .htaccess works
      
      define('WM_SiteURL',     'http://localhost/w/index.php/');
      define('WM_SystemURL',   'http://localhost/w/wmelon/');
      
      define('WM_PackagesURL', WM_SystemURL . 'packages/');
      define('WM_UploadedURL', WM_SystemURL . 'uploaded/');
      
      define('WM_SkinPath', WM_Packages    . 'installer/');
      define('WM_SkinURL',  WM_PackagesURL . 'installer/');
      
      Watermelon::$config['skin'] = 'installer';
      
      define('WM_Lang', $_SESSION['lang']);
      // define('WM_Algo', $w['algo']);
      
      // determining step number
      
      $step = (int) Watermelon::$segments[0];
      
      if($step < 1 || $step > 8)
      {
         $step = 1;
      }
      
      // loading translations
      /*
      if($step >= 3)
      {
         $this->load->translation('installer');
      }
      */
      // progress percent
      
      if($step >= 3)
      {
         $this->additionalData->progress = (int) (($step - 1) / 6 * 100);
      }
      
      // previous step
      
      if($step >= 4)
      {
         $this->additionalData->previous = $step - 1;
      }
      else
      {
         $this->additionalData->previous = null;
      }
      
      // next step
      
      if($step >= 3)
      {
         $this->additionalData->next = $step + 1;
      }
      
      // running proper step action
      
      switch(Watermelon::$segments[0])
      {
         case '1':
         default: $this->langChooser(); break;
         case '2': $this->blockadeMessage(); break;
         case '3': $this->intro(); break;
         case '4': $this->dbInfo(); break;
         case '5': $this->userdata(); break;
         case '6': $this->websiteName(); break;
         case '7': $this->thank(); break;
      }
   }
   
   /*
    * First step - language chooser
    */
   
   public function langChooser()
   {
      $this->additionalData = 'no-container'; // we want to display lang chooser on its own, without typical container
      
      $langs = array();
      
      $langs[] = array('pl', 'Polski');
      //$langs[] = array('en', 'English (US)');
      
      $view = View('langChooser');
      $view->langs = $langs;
      $view->display();
   }
   
   /*
    * Second step - message saying to create file with requested name
    * 
    * (it's a protection against someone else installing it)
    */
   
   public function blockadeMessage()
   {
      $this->additionalData = 'no-container';
      
      // determining language
      
      $lang = Watermelon::$segments[1];
      
      $langs = array('pl');
      
      if(!in_array($lang, $langs))
      {
         $lang = 'pl';
      }
      
      $_SESSION['lang'] = $lang;
      define('WM_Lang', $lang);
      
      var_dump($lang);
      
      // redirecting later (will implement blockade later)
      
      SiteRedirect('3');
   }
   
   /*
    * Third step - greeting
    */
   
   public function intro()
   {
      $this->pageTitle = 'Witaj';
      
      View('greeting')->display();
   }
   
   /*
    * fourth step - DB info
    */
   
   public function dbInfo()
   {
      $this->pageTitle = 'Dane do bazy danych';
      
      View('dbInfo')->display();
   }
   
   /*
    * fifth step - admin username and password
    */
   
   public function userdata()
   {
      $this->pageTitle = 'Dane użytkownika';
      
      View('userdata')->display();
   }
   
   /*
    * sixth step - website name
    */
   
   public function websiteName()
   {
      $this->pageTitle = 'Nazwa strony';
      
      View('websiteName')->display();
   }
   
   /*
    * seventh step - thank
    */
   
   public function thank()
   {
   }
}