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
      // .htaccess
      
      if(file_exists(WM_System . '../dot.htaccess'))
      {
         rename(WM_System . '../dot.htaccess', WM_System . '../.htaccess');
      }
      
      // URL-s
      
      if(isset($_SESSION['siteURL']))
      {
         $siteURL   = $_SESSION['siteURL'];
         $systemURL = $_SESSION['systemURL'];
      }
      else
      {
         list($siteURL, $systemURL) = $this->urls();
      }
      
      // constants
      
      define('WM_SiteURL',   $siteURL);
      define('WM_SystemURL', $systemURL);
      define('WM_CurrURL',   $siteURL);
      
      define('WM_BundlesURL',  WM_SystemURL . 'bundles/');
      define('WM_UploadedURL', WM_SystemURL . 'uploaded/');
      
      define('WM_SkinPath', WM_Bundles    . 'installer/');
      define('WM_SkinURL',  WM_BundlesURL . 'installer/');
      
      Watermelon::$config->skin = 'installer';
      
      // determining step number
      
      $step = (int) $this->segments[0];
      
      if($step < 1 || $step > 8)
      {
         $step = 1;
      }
      
      // progress percent
      
      $this->additionalData->progress = (int) (($step - 1) / 6 * 100);
      
      // previous step (but you can't go back after you unblock the blockade)
      
      if(/*$step == 2 ||*/ $step == 3 || $step >= 5)
      {
         $this->additionalData->previous = $step - 1;
      }
      else
      {
         $this->additionalData->previous = null;
      }
      
      // next step
      
      $this->additionalData->next = $step + 1;
      
      // checking if blockade is unlocked
      
      if($step > 3)
      {
         $fileName = $_SESSION['unblocking-filename'];
         
         if(!file_exists(WM_System . $fileName) || !isset($_SESSION['unblocking-filename']))
         {
            $this->addMessage('error', 'Hmm... Nie widzę pliku. Spróbuj jeszcze raz.');
            
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
         
         case 'wmelonInstallerTest':
            $this->outputType = self::Plain_OutputType;
            echo 'It works!';
         break;
         
         case 'clear':
            session_destroy();
            SiteRedirect('1');
         break;
      }
   }
   
   /*
    * private array urls()
    * 
    * Determines URL-s to website
    * 
    * returns array($siteURL, $systemURL)
    */
   
   private function urls()
   {
      // determining URL to index.php
      
      $url = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; // full URL
      
      $pathInfo = $_SERVER['PATH_INFO'];                                  // everything what is after index.php
      
      if(!empty($pathInfo))
      {
         $url = substr($url, 0, -strlen($pathInfo));                      // URL to index.php
      }
      
      // deleting '/' from URL if present
      
      if(substr($url, -1) == '/')
      {
         $url = substr($url, 0, -1);
      }
      
      // deleting 'index.php' from URL if present
      
      if(substr($url, -9) == 'index.php')
      {
         $url = substr($url, 0, -9);
      }
      
      // appending '/' to URL if absent
      
      if(substr($url, -1) != '/')
      {
         $url .= '/';
      }
      
      // determining whether .htaccess works - by trying to request special page 'watermelonurltest' (without index.php)
      
      $errorReporting = error_reporting();
      
      error_reporting(0);
      
      $urlTestResponse = file_get_contents($url . 'watermelonurltest');
      
      error_reporting($errorReporting);
      
      // determining final URLs
      
      if($urlTestResponse != 'Works!')
      {
         $siteURL = $url . 'index.php/';
      }
      else
      {
         $siteURL = $url;
      }
      
      $systemURL = $url . 'wmelon/';
      
      // returns
      
      return array($siteURL, $systemURL);
   }
   
   /*
    * First step - language chooser
    */
   
   public function langChooser()
   {
      SiteRedirect('2'); // won't be needed for now
      
      /*
      
      $this->additionalData->noContainer = true; // we want to display lang chooser on its own, without typical container
      
      $langs = array();
      
      $langs[] = array('pl', 'Polski', WM_SkinURL . 'img/pl.png');
      
      $view = View('langChooser');
      $view->langs = $langs;
      $view->display();
      
      //$this->urls();*/
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
      /*
      // determining language
      
      $lang = $this->segments[1];
      
      if(!in_array($lang, array('pl')))
      {
         $lang = 'pl';
      }
      
      $_SESSION['lang'] = $lang;
      
      define('WM_Lang', $lang);*/
      
      // establishing name of file unblocking the installer
      
      $fileName = 'wm-' . uniqid() . '.php';
      
      $_SESSION['unblocking-filename'] = $fileName;
      
      // displaying
      
      $this->pageTitle = 'Blokada';
      
      $view = View('blockade');
      $view->fileName = $fileName;
      $view->display();
   }
   
   /*
    * fourth step - DB info
    */
   
   public function dbInfo()
   {
      // default values
      
      if(isset($_SESSION['dbForm']))
      {
         $data = $_SESSION['dbForm'];
      }
      else
      {
         $data = array
            (
               'name' => 'watermelon',
               'user' => '',
               'pass' => '',
               'host' => 'localhost',
               'prefix' => 'wm_'
            );
         
         $data = ToObject($data);
      }
      
      // rendering options
      
      $this->pageTitle = 'Dane do bazy danych';
      $this->additionalData->form = true;
      
      // form
      
      $form = new Form('wmelon.installer.dbInfo', '5', '4');
      $form->displaySubmitButton = false;
      $form->extraFormAttributes['name'] = 'form';
      
      // label note
      
      $nameNote   = 'Baza o podanej nazwie musi ustnieć';
      $hostNote   = 'Prawie zawsze jest to <em>localhost</em>';
      $prefixNote = 'Zostaw taki jaki jest, chyba że chcesz mieć kilka kopii Watermelona na jednej bazie danych - wtedy obie muszą mieć ustalony inny prefiks';
      
      // input args
      
      $nameArgs   = array('value' => $data->name,   'labelNote' => $nameNote);
      $userArgs   = array('value' => $data->user);
      $passArgs   = array('value' => $data->pass);
      $hostArgs   = array('value' => $data->host,   'labelNote' => $hostNote);
      $prefixArgs = array('value' => $data->prefix, 'labelNote' => $prefixNote);
      
      // adding inputs
      
      $form->addInput('text', 'name', 'Nazwa bazy danych', true, $nameArgs);
      $form->addInput('text', 'user', 'Nazwa użytkownika', true, $userArgs);
      $form->addInput('password', 'pass', 'Hasło', false, $passArgs);
      
      $form->addHTML('<div class="advanced-hr">Zaawansowane<hr /></div>');
      
      $form->addInput('text', 'host', 'Serwer', true, $hostArgs);
      $form->addInput('text', 'prefix', 'Prefiks nazw tabel', false, $prefixArgs);
      
      echo $form->generate();
   }
   
   /*
    * fifth step - admin username and password.
    * 
    * Validating DB info, but not yet importing tables to database (it will be done in last step)
    */
   
   public function userdata()
   {
      // validating DB info
      
      if($_SESSION['previousStep'] == 4)
      {
         $form = Form::validate('wmelon.installer.dbInfo', '4');
         $data = $form->getAll();
         
         $_SESSION['dbForm'] = $data;
         
         // checking whether it's possible to connect using given data

         try
         {
            DB::connect($data->host, $data->name, $data->user, $data->pass, $data->prefix);
         }
         catch(WMException $e)
         {
            if($e->stringCode() == 'DB:connectError')
            {
               $form->addError('Nie udało się połączyć z serwerem bazy danych za pomocą podanych danych. Spróbuj jeszcze raz.');
               $form->fallback();
            }
            elseif($e->stringCode() == 'DB:selectError')
            {
               $form->addError('Nie udało się wybrać bazy danych "' . $data->name . '". Spróbuj jeszcze raz.');
               $form->fallback();
            }
         }
      }
      
      // default values
      
      if(isset($_SESSION['userDataForm']))
      {
         $data = $_SESSION['userDataForm'];
      }
      else
      {
         $data = ToObject(array('user' => '','pass' => '','pass2' => ''));
      }
      
      // rendering options
      
      $this->pageTitle = 'Dane admina';
      $this->additionalData->form = true;
      
      // form
      
      $form = new Form('wmelon.installer.userData', '6', '5');
      $form->displaySubmitButton = false;
      $form->extraFormAttributes['name'] = 'form';
      
      // label notes
      
      $pass2Note = 'Aby upewnić się, że nie popełnisz błędu podczas wpisywania';
      
      // input args
      
      $userArgs  = array('value' => $data->user);
      $passArgs  = array('value' => $data->pass);
      $pass2Args = array('value' => $data->pass2, 'labelNote' => $pass2Note);
      
      // adding args
      
      $form->addInput('text', 'user', 'Nazwa użytkownika', true, $userArgs);
      $form->addInput('password', 'pass', 'Hasło', true, $passArgs);
      $form->addInput('password', 'pass2', 'Hasło (powtórz)', true, $pass2Args);
      
      // rendering
      
      echo '<p>Podaj nick i hasło, które chcesz mieć dla siebie na swojej stronie.</p>';
      echo $form->generate();
   }
   
   /*
    * sixth step - website name
    * 
    * And validating user data form
    */
   
   public function websiteName()
   {
      // validating userdata form
      
      if($_SESSION['previousStep'] == 5)
      {
         $form = Form::validate('wmelon.installer.userData', '5');
         $data = $form->getAll();
         
         $_SESSION['userDataForm'] = $data;
         
         // checking whether password match
         
         if($data->pass != $data->pass2)
         {
            $form->addError('Podane hasła się nie zgadzają');
            $form->fallback();
         }
      }
      
      // default values
      
      if(isset($_SESSION['siteNameForm']))
      {
         $data = $_SESSION['siteNameForm'];
      }
      else
      {
         $data = ToObject(array('siteName' => ''));
      }
      
      // rendering options
      
      $this->pageTitle = 'Nazwa strony';
      $this->additionalData->form = true;
      
      // form
      
      $form = new Form('wmelon.installer.siteName', '7', '6');
      $form->displaySubmitButton = false;
      $form->extraFormAttributes['name'] = 'form';
      
      $siteNameArgs = array('value' => $data->siteName);
      
      $form->addInput('text', 'siteName', 'Nazwa strony', true, $siteNameArgs);
      
      echo '<p>Już blisko! Podaj jeszcze tylko nazwę dla Twojej nowej strony.</p>';
      echo $form->generate();
   }
   
   /*
    * seventh step - thank
    */
   
   public function thank()
   {
      // checking whether all required fields are filled
      
      if($_SESSION['previousStep'] == 6)
      {
         $form = Form::validate('wmelon.installer.siteName', '6');
         $data = $form->getAll();
         
         $_SESSION['siteNameForm'] = $data;
      }
      
      // rendering
      
      $this->pageTitle = 'Dzięki!';
      
      $view = View('thank');
      $view->db   = clone $_SESSION['dbForm'];
      $view->user = clone $_SESSION['userDataForm'];
      $view->site = clone $_SESSION['siteNameForm'];
      
      $view->db->pass   = $this->starPassword($view->db->pass);
      $view->user->pass = $this->starPassword($view->user->pass);
      
      $view->display();
   }
   
   /*
    * eighth step - saving configuration
    */
   
   public function save()
   {
      // configuration
      
      $db   = $_SESSION['dbForm'];
      $user = $_SESSION['userDataForm'];
      $site = $_SESSION['siteNameForm'];
      
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
      
      file_put_contents(WM_System . 'config.php', $configFile);
      
      // installing SQL
      
      DB::connect($db->host, $db->name, $db->user, $db->pass, $db->prefix);
      
      $structure = file_get_contents(WM_Bundles . 'installer/structure.sql');
      $data      = file_get_contents(WM_Bundles . 'installer/data.sql');
      
      $sql = $structure . "\n\n" . $data; 
      $sql = explode(';', $sql);
      
      foreach($sql as $query)
      {
         $query = trim($query);
         
         if(empty($query))
         {
            continue;
         }
         
         $query = str_replace('`wm_', '`' . $db->prefix, $query);
         
         DB::query($query, time());
      }
      
      // feeds
      
      $atomID = WM_SiteURL . time() . mt_rand();
      $atomID = sha1($atomID);
      
      // adding wmelon configuration to Registry
         
         Registry::create('wmelon', $w, true);
         
         // modules
         
         $w->modulesList       = Watermelon::indexModules();
         $w->autoload          = array('auth', 'comments', 'sblam');
         $w->defaultController = 'blog';
         
         // other
         
         $w->siteURL           = WM_SiteURL;
         $w->systemURL         = WM_SystemURL;
         
         $w->skin              = 'wcmslay';
         $w->atomID            - $atomID;
         
         // frontend
         
         $textMenus = array(array
            (
               array('Blog', 'blog', true, null),
               array('Login', 'auth/login', true, null),
            ));
         
         $blockMenus = array(array());
         
         $w->siteName   = $site->siteName;
         $w->siteSlogan = null;
         $w->footer     = '<small><a href="%/">Panel Admina</a></small>';
         $w->blockMenus = $blockMenus;
         $w->textMenus  = $textMenus;
         
         $w->headTags   = '';
         $w->tailTags   = '';
         
         // setting config
         
         Registry::set('wmelon', $w);
         
         Watermelon::$config = $w;
      
      // generating feed
      
      include WM_Core . 'Textile/textile.extension.php';
      Textile::onAutoload();
      
      Loader::extension('Auth');
      Auth::onAutoload();
      
      Model('blog')->generateFeed();
      
      // adding superuser
      
      $salt = substr(HashString(mt_rand()), 0, 16);
      $pass = HashString($user->pass . $salt);
      
      DB::insert('users', array
         (
            'login'    => strtolower($user->user),
            'password' => $pass,
            'salt'     => $salt,
            'nick'     => $user->user,
            'lastseen' => time()
         ));
      
      // removing unblocking file
      
      unlink(WM_System . $_SESSION['unblocking-filename']);
      
      // removing session and redirecting to home page
      
      session_unset();
      
      $_SESSION['Auth_login'] = $user->user;
      $_SESSION['Auth_pass']  = $user->pass;
      
      SiteRedirect('');
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