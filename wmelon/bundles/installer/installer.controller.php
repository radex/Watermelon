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

include 'InstallerForm.php';

/*
 * Watermelon Installer
 */

class Installer_Controller extends Controller
{
   /*
    * Main method - setup and running
    */
   
   public function installer()
   {
      // URL-s
      
      $baseURL = 'http://localhost/w/';
      $siteURL = 'http://localhost/w/';
      $systemURL = 'http://localhost/w/wmelon/';
      
      // constants
      
      define('WM_BaseURL',  $baseURL);
      define('SiteURL',     $siteURL);
      define('SystemURL',   $systemURL);
      define('CurrURL',     $siteURL);
      
      define('BundlesURL',  SystemURL . 'bundles/');
      define('UploadedURL', SystemURL . 'uploaded/');
      
      define('SkinPath',    BundlesPath . 'installer/');
      define('SkinURL',     BundlesURL  . 'installer/');
      
      // skin
      
      Watermelon::$config->skin = 'installer';
      
      /**************************************************************************/
      
      // validators
      
      if($this->requestURL == 'test.json')
      {
         return $this->testValidate();
      }
      
      // loading views representing installer steps
      
      $views = array();
      
      $views[] = $this->greetingView();
                                             // TODO: checking permissions, .htacceses and stuff
      $views[] = $this->dbInfoView();
      $views[] = $this->userDataView();
      $views[] = $this->websiteNameView();
      $views[] = $this->thanksView();
      
      $this->data->steps = $views;
   }
   
   
   /**************************************************************************/
   
   /*
    * Greeting view
    */
   
   public function greetingView()
   {
      return View('greeting')->generate();
   }
   
   /*
    * Database info form view
    */
   
   public function dbInfoView()
   {
      // defalult values
      
      $data = (object) array
         (
            'name'   => 'watermelon',
            'user'   => '',
            'pass'   => '',
            'host'   => 'localhost',
            'prefix' => ''
         );
      
      // form
      
      $form = new InstallerForm('wmelon.installer.dbInfo');
      
      // label note
      
      $nameNote   = 'Jeśli nie istnieje, instalator spróbuje ją utworzyć';
      $userNote   = 'Użytkownik z dostępem do podanej bazy danych';
      
      $prefixNote = 'Niezbędny jeśli chcesz mieć dwie kopie Watermelona na jednej bazie danych';
      $hostNote   = 'Prawie zawsze jest to <em>localhost</em>';
      
      // input args
      
      $nameArgs   = array('value' => $data->name,   'labelNote' => $nameNote);
      $userArgs   = array('value' => $data->user,   'labelNote' => $userNote);
      $passArgs   = array('value' => $data->pass);
      $prefixArgs = array('value' => $data->prefix, 'labelNote' => $prefixNote);
      $hostArgs   = array('value' => $data->host,   'labelNote' => $hostNote);
      
      // adding inputs
      
      $form->addInput('text',     'name',    'Nazwa bazy danych',  true,  $nameArgs);
      $form->addInput('text',     'user',    'Użytkownik',         true,  $userArgs);
      $form->addInput('password', 'pass',    'Hasło',              false, $passArgs);
      
      $form->addHTML('<div class="advanced-hr">Zaawansowane<hr /></div>');
      
      $form->addInput('text',     'prefix',  'Prefiks tabel',      false, $prefixArgs);
      $form->addInput('text',     'host',    'Serwer',             true,  $hostArgs);
      
      // displaying
      
      $view = View('dbInfo');
      $view->form = $form->generate();
      
      return $view->generate();
   }
   
   /*
    * User data form view
    */
   
   public function userDataView()
   {
      // form
      
      $form = new InstallerForm('wmelon.installer.userData');
      
      // adding args
      
      $form->addInput('text',     'user',  'Nazwa użytkownika',   true, array());
      $form->addInput('password', 'pass',  'Hasło',               true, array());
      $form->addInput('password', 'pass2', 'Hasło (powtórz)',     true, array());
      
      // rendering
      
      $view = View('userData');
      $view->form = $form->generate();
      
      return $view->generate();
   }
   
   /*
    * Website name form view
    */
   
   public function websiteNameView()
   {
      // form
      
      $form = new InstallerForm('wmelon.installer.siteName');
      
      // adding inputs
      
      $form->addInput('text', 'siteName', 'Nazwa strony', true, array());
      
      // rendering
      
      $view = View('siteName');
      $view->form = $form->generate();
      
      return $view->generate();
   }
   
   /*
    * Thanks for using Watermelon view
    */
   
   public function thanksView()
   {
      return View('thanks')->generate();
   }
   
   /**************************************************************************/
   
   public function testValidate()
   {
      $data = 'ok';
      
      $this->outputJSON($data);
   }
   
   /**************************************************************************/
   
   /*
    * Main method - setting some constants, and running proper method
    */
   
   public function _installer()
   {
      // .htaccess
      
      if(file_exists(SystemPath . '../dot.htaccess'))
      {
         rename(SystemPath . '../dot.htaccess', SystemPath . '../.htaccess');
      }
      
      // installer/
      
      if(file_exists(SystemPath . 'installer/'))
      {
         // http://php.net/manual/en/function.rmdir.php#98622
         // (too lazy to write my own)
         
         function rrmdir($dir) { 
            if (is_dir($dir)) { 
               $objects = scandir($dir); 
               foreach ($objects as $object) { 
                  if ($object != "." && $object != "..") { 
                     if (filetype($dir."/".$object) == "dir") rrmdir($dir."/".$object); else unlink($dir."/".$object); 
                  } 
               } 
               reset($objects); 
               rmdir($dir); 
            } 
         }
         
         rrmdir(SystemPath . 'installer/');
      }
      
      // storing current URL in session and retrieving one already stored
      // that's used in below algorithm for determining base url - if current URL is the same as previous one,
      // base url is determined again (because it means page was reloaded in browser,
      // perhaps because .htaccess config was changed)
      /*
      if(isset($_SESSION['wmelon.installer.previousURL']))
      {
         $previousURL = $_SESSION['wmelon.installer.previousURL'];
      }
      
      $currentURL = $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
      
      $_SESSION['wmelon.installer.previousURL'] = $currentURL;
      
      $pageReloaded = ($previousURL == $currentURL);
      
            //FIXME!
      
      */
      // URL-s
      
      $baseURL = $this->baseURL();
      
      if(isset($_SESSION['wmelon.installer.siteURL']) && isset($_SESSION['wmelon.installer.systemURL'])/* && !$pageReloaded*/)
         // already set and page is not reloaded (reasons explained above)
      {
         $siteURL   = $_SESSION['wmelon.installer.siteURL'];
         $systemURL = $_SESSION['wmelon.installer.systemURL'];
      }
      elseif(isset($_GET['urltested']))
      {
         // got back from url test
         
         $systemURL = $baseURL . 'wmelon/';
         
         if(isset($_GET['works']))
         {
            $siteURL = $baseURL;
         }
         else
         {
            $siteURL = $baseURL . 'index.php/';
         }
         
         $_SESSION['wmelon.installer.siteURL'] = $siteURL;
         $_SESSION['wmelon.installer.systemURL'] = $systemURL;
         
         Redirect($siteURL);
      }
      else
      {
         // base URL not yet determined
         // description of how it works is in wmelon/core/urltest.php
         
         $installerURL = $baseURL . 'index.php?urltested';
         
         $testfileURL = $baseURL . 'wmelon/core/urltest.php?backto=' . base64_encode($installerURL);
         
         Redirect($testfileURL);
      }
      
      // constants
      
      define('WM_BaseURL',   $baseURL);
      define('SiteURL',   $siteURL);
      define('SystemURL', $systemURL);
      define('CurrURL',   $siteURL);
      
      define('BundlesURL',  SystemURL . 'bundles/');
      define('UploadedURL', SystemURL . 'uploaded/');
      
      define('SkinPath', BundlesPath    . 'installer/');
      define('SkinURL',  BundlesURL . 'installer/');
      
      Watermelon::$config->skin = 'installer';
      
      // determining step number
      
      $step = (int) $this->segments[0];
      
      if($step < 2 || $step > 7)
      {
         $step = 2;
      }
      
      // progress percent
      
      $this->data->progress = (int) (($step - 1) / 5 * 100);
      
      // previous step (but you can't go back after you unblock the blockade)
      
      if($step >= 3)
      {
         $this->data->previous = $step - 1;
      }
      else
      {
         $this->data->previous = null;
      }
      
      // next step
      
      $this->data->next = $step + 1;
      
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
         case '2':
         default:  $this->greeting(); break;
         case '3': $this->dbInfo(); break;
         case '4': $this->userdata(); break;
         case '5': $this->websiteName(); break;
         case '6': $this->thank(); break;
         case '7': $this->save(); break;
         
         case 'clear':
            session_destroy();
            SiteRedirect('1');
         break;
      }
   }
   
   /*
    * private string baseURL()
    * 
    * Determines base URL of website
    */
   
   private function baseURL()
   {
      // determining URL to index.php
      
      $url = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; // full URL
      
      $pathInfo  = $_SERVER['PATH_INFO'];                                 // everything what is after index.php
      $queryInfo = $_SERVER['QUERY_STRING'];                              // everything what is after ?
      
      // stripping PATH_INFO
      
      if(!empty($pathInfo))
      {
         $url = substr($url, 0, -strlen($pathInfo));
      }
      
      // stripping QUERY_STRING
      
      if(!empty($queryInfo))
      {
         $queryInfo = '?' . $queryInfo;
         
         $url = substr($url, 0, -strlen($queryInfo));
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
      
      // returns
      
      return $url;
   }
   
   /**************************************************************************/
   
   public function validateDb()
   {
      $form = InstallerForm::validate('wmelon.installer.dbInfo');
      $data = $form->get();
      
      $_SESSION['dbForm'] = $data;
      
      // check if database name and prefix are valid
      
      if(!preg_match('/^[a-zA-Z0-9_]+$/', $data->name))
      {
         $form->addError('Nazwa bazy danych jest niepoprawna - dozwolone są jedynie litery, cyfry oraz znak "_"');
      }
      
      if(!preg_match('/^[a-zA-Z0-9_]*$/', $data->prefix))
      {
         $form->addError('Prefiks nazw tabel jest niepoprawny - dozwolone są jedynie litery, cyfry oraz znak "_"');
      }
      
      // checking whether it's possible to connect using given data

      try
      {
         DB::connect($data->host, $data->name, $data->user, $data->pass, $data->prefix);
      }
      catch(WMException $e)
      {
         if($e->getCode() == 'DB:connectError')
         {
            $form->addError('Nie mogę się połączyć z bazą danych przy użyciu podanych informacji.<br>Sprawdź ich poprawność i spróbuj ponownie.');
            $form->fallback();
         }
         elseif($e->getCode() == 'DB:selectError')
         {
            // can't select database - try to create one first
            
            try
            {
               $testDatabase = substr(md5(mt_rand()), 0, 16);
               
               DB::query('CREATE DATABASE ' . $testDatabase);
               DB::query('DROP DATABASE ' . $testDatabase);
            }
            catch(WMException $e)
            {
               // don't have privileges to create database
               
               $form->addError('Zdaje się, że baza danych "' . $data->name . '" nie istnieje, a użytkownik "' . $data->user . '" nie ma uprawnień do jej utworzenia.<br><br>Sprawdź, czy podane dane nie zawierają błędu lub spróbuj utworzyć bazę danych ręcznie w panelu administracyjnym serwera i spróbuj ponownie.');
               $form->fallback();
            }
         }
      }
      
      // if any errors, fall back
   
      $form->fallBack();
   }
   
   
   public function validateUserData()
   {
      $form = InstallerForm::validate('wmelon.installer.userData');
      $data = $form->get();
      
      $_SESSION['userDataForm'] = $data;
      
      // checking whether password match
      
      if($data->pass != $data->pass2)
      {
         $form->addError('Podane hasła nie są takie same. Popraw i spróbuj ponownie.');
         $form->fallback();
      }
   }
   
   /*
    * Seventh step - saving configuration
    */
   
   public function _save()
   {
      // configuration
      
      $db    = $_SESSION['dbForm'];
      $user  = $_SESSION['userDataForm'];
      $site  = $_SESSION['siteNameForm'];
      
      // generating Atom ID for website
      
      $atomID = SiteURL . time() . mt_rand();
      $atomID = sha1($atomID);
      
      // connecting with database
      
      try
      {
         DB::connect($db->host, $db->name, $db->user, $db->pass, $db->prefix);
      }
      catch(WMException $e)
      {
         // creating database if necessary
         
         if($e->getCode() == 'DB:selectError')
         {
            DB::query('CREATE DATABASE ' . $db->name);
            
            DB::connect($db->host, $db->name, $db->user, $db->pass, $db->prefix);
         }
         else
         {
            throw $e;
         }
      }
      
      // installing SQL
      
         $path = BundlesPath . 'installer/';
         
         // SQL files
         
         $structure = file_get_contents($path . 'sql/structure.sql');
         $data      = file_get_contents($path . 'sql/data.sql');
         
         // sample content files
         
         $samplePostSummary = file_get_contents($path . 'sql/samplePostSummary.txt');
         $samplePost        = file_get_contents($path . 'sql/samplePost.txt');
         $samplePage        = file_get_contents($path . 'sql/samplePage.txt');
         
         // generating Atom feed ID for sample post
         
         $postAtomID = $atomID . 'Witaj w Watermelonie!' . time() . mt_rand();
         $postAtomID = sha1($postAtomID);
         
         // making queries
         
         $sql = $structure . "\n\n" . $data;
         $sql = explode(';', $sql);
         
         foreach($sql as $query)
         {
            $query = trim($query);
         
            if(empty($query))
            {
               continue;
            }
            
            // substituting placeholders
            
            $query = str_replace('`wm_', '`' . $db->prefix, $query);
            $query = str_replace('{time}', time(), $query);
            $query = str_replace('{atom-id}', $postAtomID, $query);

            $query = str_replace('{post-summary}', mysql_real_escape_string($samplePostSummary), $query);
            $query = str_replace('{post-content}', mysql_real_escape_string($samplePost), $query);
            $query = str_replace('{page-content}', mysql_real_escape_string($samplePage), $query);
            
            DB::pureQuery($query);
         }
      
      // installing Watermelon's configuration
         
         // modules
         
         $w->modulesList       = Watermelon::indexModules(false);
         $w->defaultController = 'blog';
         
         // other
         
         $w->siteURL           = SiteURL;
         $w->systemURL         = SystemURL;
         
         $w->skin              = 'wcmslay';
         $w->atomID            = $atomID;
         
         // frontend
         
         $textMenus = array(array
            (
               array('Blog', '', true, null),
               array('Pomoc Watermelona', 'wmelonHelp', true, 'Opis zaawansowanych funkcji Watermelona'),
            ));
         
         $w->siteName   = $site->siteName;
         $w->siteSlogan = null;
         $w->footer     = '<small><a href="$/users/login">Logowanie</a></small><br>' .
            'powered by <strong><a href="https://github.com/radex/Watermelon">Watermelon</a></strong>';
         $w->textMenus  = $textMenus;
         
         $w->headTags   = '';
         $w->tailTags   = '';
         
         // setting config
         
         Config::set('wmelon.wmelon', $w);
         
         Watermelon::$config = $w;
      
      // generating Atom feed
      
      Blog_Model::updateFeed();
      
      // adding superuser
         
      $salt = substr(HashString(mt_rand()), 0, 16);
      $pass = HashString($user->pass . $salt);
      
      DB::insert('users', array
         (
            'login'    => strtolower($user->user),
            'password' => $pass,
            'salt'     => $salt,
            'nick'     => $user->user,
            'email'    => '',
            'lastseen' => time(),
         ));
      
      // saving config.php
      
      $configFile = file_get_contents(BundlesPath . 'installer/config.php');
      
      $configFile = str_replace('{host}',   addslashes($db->host), $configFile);
      $configFile = str_replace('{user}',   addslashes($db->user), $configFile);
      $configFile = str_replace('{pass}',   addslashes($db->pass), $configFile);
      $configFile = str_replace('{name}',   addslashes($db->name), $configFile);
      $configFile = str_replace('{prefix}', addslashes($db->prefix), $configFile);
      
      file_put_contents(SystemPath . 'config.php', $configFile);
      
      // removing session and redirecting to home page
      
      session_unset();
      
      $_SESSION['wmelon.user.login'] = $user->user;
      $_SESSION['wmelon.user.pass']  = $user->pass;
      
      SiteRedirect('');
   }
}