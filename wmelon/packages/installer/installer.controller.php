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
      
      Watermelon::$config->skin = 'installer';
      
      define('WM_Lang', $_SESSION['lang']);
      
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
      
      if($step >= 2)
      {
         $this->additionalData->progress = (int) (($step - 1) / 6 * 100);
      }
      
      // previous step (but you can't go back after you unblock the blockade)
      
      if($step == 2 || $step == 3 || $step >= 5)
      {
         $this->additionalData->previous = $step - 1;
      }
      else
      {
         $this->additionalData->previous = null;
      }
      
      // next step
      
      if($step >= 2)
      {
         $this->additionalData->next = $step + 1;
      }
      
      // checking if blockade is unlocked
      
      if($step > 3)
      {
         $fileName = $_SESSION['unblocking-filename'];
         
         if(!file_exists(WM_BasePath . $fileName) || !isset($_SESSION['unblocking-filename']))
         {
            $_SESSION['errors'] = array('Hmm... Nie widzę pliku. Spróbuj jeszcze raz.');
            
            SiteRedirect('3');
         }
      }
      
      // previous step number
      
      if(isset($_SESSION['currentStep']))
      {
         $_SESSION['previousStep'] = $_SESSION['currentStep'];
      }
      else
      {
         $_SESSION['previousStep'] = 1;
      }
      
      $_SESSION['currentStep'] = $step;
      
      //TODO: here!
      
      // running proper step action
      
      switch(Watermelon::$segments[0])
      {
         case '1':
         default: $this->langChooser(); break;
         case '2': $this->greeting(); break;
         case '3': $this->blockadeMessage(); break;
         case '4': $this->dbInfo(); break;
         case '5': $this->userdata(); break;
         case '6': $this->websiteName(); break;
         case '7': $this->thank(); break;
         case '8': $this->save(); break;
         case 'clear':
            session_destroy();
         break;
      }
   }
   
   /*
    * First step - language chooser
    */
   
   public function langChooser()
   {
      $this->additionalData = 'no-container'; // we want to display lang chooser on its own, without typical container
      
      $langs = array();
      
      $langs[] = array('pl', 'Polski', WM_SkinURL . 'img/pl.png');
      
      $view = View('langChooser');
      $view->langs = $langs;
      $view->display();
   }
   
   /*
    * Second step - greeting
    */
   
   public function greeting()
   {
      $this->pageTitle = 'Witaj';
      
      View('greeting')->display();
   }
   
   /*
    * Third step - message saying to create file with requested name
    * 
    * (it's a protection against someone else installing it)
    */
   
   public function blockadeMessage()
   {
      // determining language
      
      $lang = Watermelon::$segments[1];
      
      if(!in_array($lang, array('pl')))
      {
         $lang = 'pl';
      }
      
      $_SESSION['lang'] = $lang;
      
      define('WM_Lang', $lang);
      
      // establishing name of file unblocking the installer
      
      $fileName = 'b-' . uniqid() . '.php';
      
      $_SESSION['unblocking-filename'] = $fileName;
      
      // displaying
      
      $this->pageTitle = 'Blokada';
      
      $view = View('blockade');
      $view->fileName = $fileName;
      $view->errors   = $this->errors();
      $view->display();
   }
   
   /*
    * fourth step - DB info
    */
   
   public function dbInfo()
   {
      $this->pageTitle = 'Dane do bazy danych';
      $this->additionalData->form = '';
      
      //--
      
      if(isset($_SESSION['dbForm']))
      {
         $form = ToObject($_SESSION['dbForm']);
      }
      else
      {
         $form = array
            (
               'name' => 'watermelon',
               'user' => '',
               'pass' => '',
               'host' => 'localhost',
               'prefix' => 'wm_'
            );
         
         $form = ToObject($form);
      }
      
      //--
      
      $view = View('dbInfo');
      $view->errors = $this->errors();
      $view->form = $form;
      $view->display();
   }
   
   /*
    * fifth step - admin username and password.
    * 
    * Checking correctness of given DB data,
    * but not yet importing tables to database (it will be done in last step)
    */
   
   public function userdata()
   {
      // checking correctness of given DB data
      
      if($_SESSION['previousStep'] == 4)
      {
         $this->DBvalidate();
      }
      
      // rendering
      
      $this->pageTitle = 'Dane admina';
      $this->additionalData->form = '';
      
      if(isset($_SESSION['userdataForm']))
      {
         $form = ToObject($_SESSION['userdataForm']);
      }
      else
      {
         $form = ToObject(array('user' => '','pass' => '','pass2' => ''));
      }
      
      $view = View('userData');
      $view->errors = $this->errors();
      $view->form = $form;
      $view->display();
   }
   
   /*
    * sixth step - website name
    * 
    * And checking whether username and password is filled
    */
   
   public function websiteName()
   {
      // checking whether all required fields are filled
      
      if($_SESSION['previousStep'] == 5)
      {
         $user = $_POST['user'];
         $pass = $_POST['pass'];
         $pass2 = $_POST['pass2'];
      
         $_SESSION['userdataForm'] = $_POST;
      
         if(empty($user) || empty($pass) || empty($pass2))
         {
            $_SESSION['errors'][] = 'Wszystkie pola są wymagane';
         }
         
         if(!empty($pass) && !empty($pass2) && $pass != $pass2)
         {
            $_SESSION['errors'][] = 'Podane hasła się różnią';
         }
         
         if(!empty($_SESSION['errors']))
         {
            SiteRedirect('5');
         }
      }
      
      // rendering
      
      $this->pageTitle = 'Nazwa strony';
      $this->additionalData->form = '';
      
      if(isset($_SESSION['sitenameForm']))
      {
         $form = ToObject($_SESSION['sitenameForm']);
      }
      else
      {
         $form = ToObject(array('siteName' => ''));
      }
      
      $view = View('websiteName');
      $view->errors = $this->errors();
      $view->form = $form;
      $view->display();
   }
   
   /*
    * seventh step - thank
    */
   
   public function thank()
   {
      // checking whether all required fields are filled
      
      if($_SESSION['previousStep'] == 6)
      {
         $siteName = $_POST['siteName'];
      
         $_SESSION['sitenameForm'] = $_POST;
      
         if(empty($siteName))
         {
            $_SESSION['errors'] = array('Pole jest wymagane');
         
            SiteRedirect('6');
         }
      }
      
      // rendering
      
      $this->pageTitle = 'Dzięki!';
      
      $view = View('thank');
      $view->db   = ToObject($_SESSION['dbForm']);
      $view->user = ToObject($_SESSION['userdataForm']);
      $view->site = ToObject($_SESSION['sitenameForm']);
      
      $view->db->pass = $this->starPassword($view->db->pass);
      $view->user->pass = $this->starPassword($view->user->pass);
      
      $view->display();
   }
   
   /*
    * eighth step - saving configuration
    */
   
   public function save()
   {
      // configuration
      
      $db   = ToObject($_SESSION['dbForm']);
      $user = ToObject($_SESSION['userdataForm']);
      $site = ToObject($_SESSION['sitenameForm']);
      
      // saving config.php
      
      $configFile = <<<CONFIG
<?php

/*   Database   */

\$dbHost     = '$db->host';
\$dbUser     = '$db->user';
\$dbPass     = '$db->pass';
\$dbName     = '$db->name';
\$dbPrefix   = '$db->prefix';

/*   Advanced   */

\$debugLevel = 0; // 0 - no debug notices, no error reporting; real world applications
                 // 1 - debug notices, E_ALL ^ E_NOTICE error reporting; programming
                 // 2 - debug notices, E_ALL error reporting; testing & debugging
CONFIG;
      
      file_put_contents(WM_BasePath . 'config.php', $configFile);
      
      // installing SQL
      
      DB::connect($db->host, $db->name, $db->user, $db->pass, $db->prefix);
      
      $sql = file_get_contents(WM_Packages . 'installer/watermelon.sql');
      $sql = explode(';', $sql);
      
      foreach($sql as $query)
      {
         $query = trim($query);
         
         if(empty($query))
         {
            continue;
         }
         
         $query = str_replace('`wm_', '`' . $db->prefix, $query);
         
         DB::query($query);
      }
      
      // adding wmelon configuration to Registry
      
      //TODO: make it better
      
      $w->modulesList         = new stdClass;
      $w->autoload          = array('test', 'test2');
      $w->controllerHandler = null;
      $w->defaultController = 'test';
      
      $w->siteURL           = WM_SiteURL;
      $w->systemURL         = WM_SystemURL;
      
      $w->skin              = 'wcmslay';
      $w->lang              = WM_Lang;
      
      $w->siteName   = $site->siteName;
      $w->siteSlogan = '';
      $w->footer     = '';
      $w->blockMenus = array();
      $w->textMenus  = array();
      
      Registry::create('wmelon', $w, true);
      
      // adding superuser
      
      //TODO: adding superuser
      
      // removing session and redirecting to home page
      
      //TODO: auto-logging
      
      session_destroy();
      
      SiteRedirect('');
   }
   
   /*
    * errors
    */
   
   private function errors()
   {
      if(empty($_SESSION['errors']))
      {
         return;
      }
      
      $errors = $_SESSION['errors'];
      
      unset($_SESSION['errors']);
      
      // composing
      
      foreach($errors as $error)
      {
         $ret .= '<div class="error-box">';
         $ret .= $error;
         $ret .= '</div>';
      }
      
      //--
      
      return $ret;
   }
   
   /*
    * checking correctness of given DB data
    */
   
   private function DBValidate()
   {
      $name = $_POST['name'];
      $user = $_POST['user'];
      $pass = $_POST['pass'];
      $host = $_POST['host'];
      $prefix = $_POST['prefix'];
      
      $_SESSION['dbForm'] = $_POST;
      
      $errors = array();
      
      $fieldNames = array
         (
            'name' => 'nazwa bazy danych',
            'user' => 'nazwa użytkownika',
            'host' => 'serwer',
         );
      
      // checking whether all required fields are filled
      
      $emptyFields = array();
      
      foreach(array('name', 'user', 'host') as $field)
      {
         if(empty($$field))
         {
            $emptyFields[] = '"' . $fieldNames[$field] . '"';
         }
      }
      
      if(count($emptyFields) >= 2)
      {
         $errors[] = 'Pola ' . implode(', ', $emptyFields) . ' nie mogą być puste';
      }
      elseif(count($emptyFields) == 1)
      {
         $errors[] = 'Pole ' . $emptyFields[0] . ' nie może być puste';
      }
      
      if(!empty($errors))
      {
         $_SESSION['errors'] = $errors;
         
         SiteRedirect('4');
      }
      
      // checking whether it's possible to connect using given data
      
      try
      {
         DB::connect($host, $name, $user, $pass, $prefix);
      }
      catch(WMException $e)
      {
         if($e->stringCode() == 'DB:connectError')
         {
            $_SESSION['errors'][0] = 'Nie udało się połączyć z serwerem bazy danych za pomocą podanych danych. Spróbuj jeszcze raz.';
            
            SiteRedirect('4');
         }
         elseif($e->stringCode() == 'DB:selectError')
         {
            $_SESSION['errors'][0] = 'Nie udało się wybrać bazy danych "' . $name . '". Spróbuj jeszcze raz.';
            
            SiteRedirect('4');
            
            //TODO: try to create database
         }
      }
   }
   
   /*
    * obscures password (e.g. changes qwerty to q****y)
    */
   
   private function starPassword($pass)
   {
      $len = strlen($pass);
      
      if($len < 4)
      {
         return str_repeat('*', $len) . ' (' . $len . ')';
      }
      else
      {
         return $pass[0] . str_repeat('*', $len - 2) . $pass[$len - 1] . ' (' . $len . ')';
      }
   }
}