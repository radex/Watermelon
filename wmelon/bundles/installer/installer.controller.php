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
 * Watermelon Installer
 */

class Installer_Controller extends Controller
{
   private $tableNames = array('benchmark', 'blogposts', 'categories', 'comments', 'config', 'pages', 'privileges', 'users');
   
   /*
    * Main method - setup and running
    */
   
   public function installer()
   {
      // URL-s
      
      $baseURL = $this->baseURL();
      $siteURL = $baseURL . 'index.php';
      $systemURL = $baseURL . 'wmelon/';
      
      // constants
      
      define('SiteURL',     $siteURL);
      define('SystemURL',   $systemURL);
      define('CurrURL',     $siteURL);
      
      define('BundlesURL',  SystemURL . 'bundles/');
      
      define('SkinPath',    BundlesPath . 'installer/');
      define('SkinURL',     BundlesURL  . 'installer/');
      
      // skin
      
      Watermelon::$config->skin = 'installer';
      
      // validators etc.
      
      if($this->requestURL == 'db.json')
      {
         return $this->dbValidate();
      }
      elseif($this->requestURL == 'permissions.json')
      {
         return $this->outputJSON($this->permissions());
      }
      
      // displaying views representing installer steps
      
      View('greeting')->display();
      $this->permissionsView();
      View('dbInfo')->display();
      View('userData')->display();
      View('siteName')->display();
   }
   
   /**************************************************************************/
   
   /*
    * Displays view of step regarding files/directories permissions
    */
   
   public function permissionsView()
   {
      //TODO
   }
   
   /**************************************************************************/
   
   /*
    * Checks permissions to write for specific files and directories
    * 
    * Returns as an array
    */
   
   public function permissions()
   {
      if(file_exists(SystemPath . '../watermelon.htaccess'))
      {
         if(file_exists(SystemPath . '../.htaccess'))
         {
            $perms[] = array('.htaccess', is_writable(SystemPath . '../.htaccess'));
         }
         
         $perms[] = array('watermelon.htaccess', is_writable(SystemPath . '../watermelon.htaccess'));
      }
      
      $perms[] = array('wm-uploaded/',      is_writable(SystemPath . '../wm-uploaded/'));
      $perms[] = array('wmelon/cache/',     is_writable(CachePath));
      $perms[] = array('wmelon/config.php', is_writable(SystemPath . 'config.php'));
      
      return $perms;
   }
   
   /*
    * Validates database info (sent via $_POST using AJAX)
    * 
    * If there are some errors, returns JSON: ['error', ['(msg)', ...]]
    * 
    * If everything is fine, returns JSON: ['ok', prefix], where prefix is proposed table prefix
    * (if there are already Watermelon's tables in this database, appropriate prefix is proposed)
    */
   
   public function dbValidate()
   {
      $name   = $_POST['name'];
      $user   = $_POST['user'];
      $pass   = $_POST['pass'];
      $prefix = $_POST['prefix'];
      $host   = $_POST['host'];
      
      $databaseExists = true;
      
      $errors = array();
      
      // checking whether it's possible to connect using given data
      
      try
      {
         DB::connect($host, $name, $user, $pass, $prefix);
      }
      catch(WMException $e)
      {
         if($e->getCode() == 'DB:connectError')
         {
            $error = 'Nie mogę się połączyć z bazą danych przy użyciu podanych informacji.<br>Sprawdź ich poprawność i spróbuj ponownie.';
            
            return $this->outputJSON(array('error', array($error)));
         }
         elseif($e->getCode() == 'DB:selectError')
         {
            // can't select database - try to create one first
            
            try
            {
               $testDatabase = substr(md5(mt_rand()), 0, 16);
               
               DB::query('CREATE DATABASE ' . $testDatabase);
               DB::query('DROP DATABASE ' . $testDatabase);
               
               $databaseExists = false;
            }
            catch(WMException $e)
            {
               // don't have privileges to create database
               
               $error = 'Zdaje się, że baza danych "' . $name . '" nie istnieje, a użytkownik "' . $user . '" nie ma uprawnień do jej utworzenia (albo istnieje, ale użytkownik nie ma uprawnień do dostępu do niej).<br><br>Sprawdź, czy podane dane nie zawierają błędu lub spróbuj utworzyć bazę danych ręcznie w panelu administracyjnym serwera i spróbuj ponownie.';
            
               return $this->outputJSON(array('error', array($error)));
            }
         }
      }
      
      // if database exists, check if tables already exist
      // (and if they do -- generate appropriate table prefix)
      
      if($databaseExists)
      {
         $i = 0;
      
         do
         {
            $i++;
         
            // prefix
         
            if($i > 1)
            {
               $prefix = 'wm' . $i . '_';
            }
         
            // test every table name
         
            foreach($this->tableNames as $tableName)
            {
               $result = DB::pureQuery('SHOW TABLES LIKE "' . $prefix . $tableName . '"');
            
               if($result->exists)
               {
                  continue 2;
               }
            }
         
            break;
         }
         while(true);
      }
      
      // everything is fine
      
      $output = array('ok', $prefix);
      
      $this->outputJSON($output);
   }
   
   /**************************************************************************/
   
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
   }
   
   /**************************************************************************/
   
   
   
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