<?php
/********************************************************************

  Watermelon CMS

Copyright 2008 Radosław Pietruszewski

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
version 2 as published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.

********************************************************************/

$_w_startTime = microtime();
session_start();
ob_start();

header("Content-Type: text/html; charset=UTF-8");

##
## ustawienia raportowania błędów itp.
##

//error_reporting(0);
error_reporting(E_ALL ^ E_NOTICE);
//error_reporting(E_ALL);
//error_reporting(E_ALL | E_STRICT);

//define('NOMENU', '');
define('DEBUG', '');

##
## magic quotes
##

if(get_magic_quotes_gpc())
{
   function stripslashes_deep($value)
   {
      $value = is_array($value) ? array_map('stripslashes_deep', $value) : stripslashes($value);
      return $value;
   }
   $_POST = array_map('stripslashes_deep', $_POST);
   $_GET = array_map('stripslashes_deep', $_GET);
   $_COOKIE = array_map('stripslashes_deep', $_COOKIE);
   $_REQUEST = array_map('stripslashes_deep', $_REQUEST);
}

##
## wczytujemy plik z konfiguracją
##

if(file_exists('config.php'))
{
   include 'config.php';
   
   if(!defined('WTRMLN_BASEURL'))
   {
      header('Location: wtrmln/install/index.php');
      exit;
   }
}
else
{
   header('Location: wtrmln/install/index.php');
   exit;
}

##
## odpalamy główny plik CMS-a
##

include WTRMLN_CMSPATH . 'system.php';

?>
