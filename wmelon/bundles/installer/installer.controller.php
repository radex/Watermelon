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
      $siteURL = $baseURL . 'index.php/';
      $systemURL = $baseURL . 'wmelon/';
      
      // constants
      
      define('BaseURL',     $baseURL);
      define('SiteURL',     $siteURL);
      define('SystemURL',   $systemURL);
      define('CurrURL',     $siteURL);
      
      define('BundlesURL',  SystemURL . 'bundles/');
      
      define('SkinPath',    BundlesPath . 'installer/');
      define('SkinURL',     BundlesURL  . 'installer/');
      
      // skin
      
      Watermelon::$config->skin = 'installer';
      
      // validators etc.
      
      switch($this->requestURL)
      {
         case 'db.json':                  return $this->dbValidate();
         case 'permissions.json':         return $this->outputJSON($this->permissions());
         case 'permissions_after.json':   return $this->outputJSON($this->permissions_after());
         case 'install-1.json':           return $this->install1();
         case 'install-2.json':           return $this->install2();
      }
      
      // displaying views representing installer steps
      
      $files = $this->permissions();
      
      View('greeting')->display();
      $this->permissionsView($files);
      View('dbInfo')->display();
      View('userData')->display();
      View('siteName')->display();
      $this->permissions_afterView($files);
   }
   
   /**************************************************************************/
   
   /*
    * Displays view of step regarding files/directories permissions (before installation)
    */
   
   public function permissionsView($files)
   {
      // if everything is fine, go ahead
      
      if(empty($files))
      {
         return;
      }
      
      // display view
      
      $v = View('permissions');
      $v->files = $files;
      $v->display();
   }
   
   /*
    * Displays view of step regarding reverting config.php and .htaccess permissions (after installation)
    */
   
   public function permissions_afterView($files)
   {
      // if everything is fine, go ahead:
      // if required files/dirs are already writable before installation, it means that someone is either
      // testing Watermelon on localhost (and has no intention to block write perms) or know what he's doing
      
      if(empty($files))
      {
         return;
      }
      
      // display view
      
      View('permissions_after')->display();
   }
   
   /**************************************************************************/
   
   /*
    * Checks permissions to write for specific files and directories
    * 
    * Returns array of file/dir names that does not have required write permissions
    */
   
   public function permissions()
   {
      $files = array();
      
      // .htaccess / watermelon.htaccess
      
      if(file_exists(SystemPath . '../watermelon.htaccess'))
      {
         if(file_exists(SystemPath . '../.htaccess') && !is_writable(SystemPath . '../.htaccess'))
         {
            $files[] = '.htaccess';
         }
         
         if(!is_writable(SystemPath . '../watermelon.htaccess'))
         {
            $files[] = 'watermelon.htaccess';
         }
      }
      
      // others
      
      $filesToCheck = array
         (
            'wm-uploaded/'      => SystemPath . '../wm-uploaded/',
            'wmelon/cache/'     => CachePath,
            'wmelon/config.php' => SystemPath . 'config.php',
         );
      
      foreach($filesToCheck as $name => $path)
      {
         if(!is_writable($path))
         {
            $files[] = $name;
         }
      }
      
      return $files;
   }
   
   /*
    * Checks write permissions for .htaccess and config.php (it's used after installation)
    * 
    * Returns array of file names that still have (and should not) write permissions
    */
   
   public function permissions_after()
   {
      $files = array();
      
      if(file_exists(SystemPath . '../.htaccess') && is_writable(SystemPath . '../.htaccess'))
      {
         $files[] = '.htaccess';
      }
      
      if(is_writable(SystemPath . 'config.php'))
      {
         $files[] = 'wmelon/config.php';
      }
      
      return $files;
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
            $error =
            'Nie mogę połączyć się z serwerem bazy danych. Sprawdź, czy użytkownik i hasło są poprawnie wpisane. Upewnij się w panelu administracyjnym swojego serwera, czy serwer bazy danych to na pewno <em>localhost</em>. Jeśli nie, zmień pole „Serwer” schowane w „zaawansowanych”.<br><br>Naciśnij „Dalej”, aby spróbować jeszcze raz.';
            
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
               // don't have privileges to create database (or don't have privileges to database)
               
               $error =
               'Zdaje się, że baza danych „' . $name . '” nie istnieje albo użytkownik „' . $user . '” nie ma do niej dostępu.<br><br>Utwórz nową bazę danych w panelu administracyjnym swojego serwera i naciśnij „Dalej”, aby spróbować jeszcze raz.';
            
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
    * Moves watermelon.htaccess to .htaccess (or prepends .htaccess with the contents of watermelon.htaccess)
    * 
    * It's required to split it into two parts, because we need to know whether mod_rewrite in .htaccess works
    * for installation and to check that we need to make separate request (via redirection or AJAX in this case)
    * 
    * Prints just '"ok"' (JSON)
    */
   
   public function install1()
   {
      $this->plainOutput = true;
      
      // stop if no watermelon.htaccess
      
      if(!file_exists(SystemPath . '../watermelon.htaccess'))
      {
         return $this->outputJSON('ok');
      }
      
      // move htaccess or prepend existing one
      
      if(file_exists(SystemPath . '../.htaccess'))
      {
         $wmelonHtaccess = file_get_contents(SystemPath . '../watermelon.htaccess');
         $existingHtaccess = file_get_contents(SystemPath . '../.htaccess');
         
         $htaccess = $wmelonHtaccess . "\n\n\n" . $existingHtaccess;
         
         file_put_contents(SystemPath . '../.htaccess', $htaccess);
         
         // delete watermelon.htaccess
         
         unlink(SystemPath . '../watermelon.htaccess');
      }
      else
      {
         rename(SystemPath . '../watermelon.htaccess', SystemPath . '../.htaccess');
      }
      
      return $this->outputJSON('ok');
   }
   
   /*
    * Installation, part 2
    * 
    * All the other stuff -- saving config.php, installing SQL, setting initial config, creating user, etc.
    */
   
   public function install2()
   {
      $this->plainOutput = true;
      
      // data
      
      $fields = array('dbname', 'dbuser', 'dbpass', 'dbprefix', 'dbhost', 'login', 'pass', 'pass2', 'sitename');
      
      foreach($fields as $key)
      {
         $this->$key = $_POST[$key];
      }
      
      if(empty($this->sitename))
      {
         $this->sitename = 'Mój blog';
      }
      
      // URL-s
      
      $mod_rewrite = ($_POST['mod_rewrite'] == 'on');
      
      if($mod_rewrite)
      {
         $this->siteURL = BaseURL;
      }
      else
      {
         $this->siteURL = BaseURL . 'index.php/';
      }
      
      // generating Atom ID for website
      
      $atomID = SiteURL . time() . mt_rand();
      $this->atomID = sha1($atomID);
      
      // connecting with database
      
         try
         {
            DB::connect($this->dbhost, $this->dbname, $this->dbuser, $this->dbpass, $this->dbprefix);
         }
         catch(WMException $e)
         {
            // creating database if necessary
         
            if($e->getCode() == 'DB:selectError')
            {
               DB::query('CREATE DATABASE ' . $this->dbname);
            
               DB::connect($this->dbhost, $this->dbname, $this->dbuser, $this->dbpass, $this->dbprefix);
            }
            else
            {
               throw $e;
            }
         }
      
      // installing SQL
      
      $this->install_sql();
      
      // installing Watermelon's configuration
      
      $this->install_config();
      
      // generating Atom feed
      
      Blog_Model::updateFeed();
      
      // adding superuser
         
      $salt = substr(HashString(mt_rand()), 0, 16);
      $pass = HashString($this->pass . $salt);
      
      DB::insert('users', array
         (
            'login'    => strtolower($this->login),
            'password' => $pass,
            'salt'     => $salt,
            'nick'     => $this->login,
            'email'    => '',
            'lastseen' => time(),
         ));
      
      // saving config.php
      
      $configFile = file_get_contents(BundlesPath . 'installer/data/config.php');
      
      $configFile = str_replace('{host}',   addslashes($this->dbhost), $configFile);
      $configFile = str_replace('{user}',   addslashes($this->dbuser), $configFile);
      $configFile = str_replace('{pass}',   addslashes($this->dbpass), $configFile);
      $configFile = str_replace('{name}',   addslashes($this->dbname), $configFile);
      $configFile = str_replace('{prefix}', addslashes($this->dbprefix), $configFile);
      
      file_put_contents(SystemPath . 'config.php', $configFile);
      
      // setting admin session
      
      session_unset();
      
      $_SESSION['wmelon.user.login'] = $this->login;
      $_SESSION['wmelon.user.pass']  = $this->pass;
   }
   
   /**************************************************************************/
   
   /*
    * Installs SQL
    */
   
   private function install_sql()
   {
      $path = BundlesPath . 'installer/sql/';
      
      // SQL files
      
      $structure = file_get_contents($path . 'structure.sql');
      $data      = file_get_contents($path . 'data.sql');
      
      // sample content files
      
      $samplePostSummary = file_get_contents($path . 'samplePostSummary.txt');
      $samplePost        = file_get_contents($path . 'samplePost.txt');
      $samplePage        = file_get_contents($path . 'samplePage.txt');
      
      // generating Atom feed ID for sample post
      
      $postAtomID = $this->atomID . 'Witaj w Watermelonie!' . time() . mt_rand();
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
         
         $query = str_replace('`wm_', '`' . $this->dbprefix, $query);
         $query = str_replace('{time}', time(), $query);
         $query = str_replace('{atom-id}', $postAtomID, $query);

         $query = str_replace('{post-summary}', mysql_real_escape_string($samplePostSummary), $query);
         $query = str_replace('{post-content}', mysql_real_escape_string($samplePost), $query);
         $query = str_replace('{page-content}', mysql_real_escape_string($samplePage), $query);
         
         DB::pureQuery($query);
      }
   }
   
   /*
    * Installs Watermelon config (config table in db)
    */
   
   private function install_config()
   {
      // modules
      
      $w->modulesList       = Watermelon::indexModules(false);
      $w->defaultController = 'blog';
      
      // other
      
      $w->siteURL           = $this->siteURL;
      $w->systemURL         = SystemURL;
      
      $w->skin              = 'wcmslay';
      $w->atomID            = $this->atomID;
      
      // frontend
      
      $textMenus = array(array
         (
            array('Blog', '', true, null),
            array('Pomoc Watermelona', 'wmelonHelp', true, 'Opis zaawansowanych funkcji Watermelona'),
         ));
      
      $w->siteName   = $this->sitename;
      $w->siteSlogan = null;
      $w->footer     = '<small><a href="$/users/login">Logowanie</a></small><br>' .
         'powered by <strong><a href="https://github.com/radex/Watermelon">Watermelon</a></strong>';
      $w->textMenus  = $textMenus;
      
      $w->headTags   = '';
      $w->tailTags   = '';
      
      // setting config
      
      Config::set('wmelon.wmelon', $w);
      
      Watermelon::$config = $w;
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
}