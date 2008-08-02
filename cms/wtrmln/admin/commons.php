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


/*session_cache_limiter('private');
session_cache_expire(30);                            // ustawienie długości sesji
$st = session_cache_expire();*/
/* ustaw ogranicznik pamięci podręcznej na 'private' */
/*
// TODO: [fix] naprawić ten szit

session_cache_limiter('private');
$cache_limiter = session_cache_limiter();
session_cache_expire(30);

$st = session_cache_expire();
*/
session_start();
header("Content-Type: text/html; charset=UTF-8");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");            // czas wygaśnicia strony
header("Last-Modified: ".gmdate( 'D, d M Y H:i:s ' )." GMT");// czas ostatniej modyfikacji
header("Cache-Control: no-store, no-cache, must-revalidate");// wyłączenie cache'owania
header("Cache-Control: post-check=0, pre-check=0' , false"); // -||-
header("Pragma: no-cache");                                  // -||-

/* konfiguracja
*********************************************/

include('../../config.php');

define('WTRMLN_ADMIN_IS', 'TRUE');
define('WTRMLN_ADMIN_THEME', 'adminsomething');

ob_start();

/* maksymalny czas sesji - 10 minut
*********************************************/

if(!isset($_SESSION['WTRMLN_ADMIN_STARTTIME']))
{
   $_SESSION['WTRMLN_ADMIN_STARTTIME'] = time();
}
elseif($_SESSION['WTRMLN_ADMIN_STARTTIME'] < time() - 600)
{
	header('Location: ' . WTRMLN_ADMIN . 'index.php/logout');
}


/* tylko ukochana operka może używać PA :D
*********************************************/

if(ereg('Opera', $_SERVER['HTTP_USER_AGENT']) != 1)
{
  //exit;
}

/* funkcje pomocnicze
**********************************************/
include WTRMLN_HELPERS . 'helpers.php';

/* baza danych
**********************************************/

include WTRMLN_LIBS . 'db.php';

$_db = new DB();
$_db->connect($_w_dbHost, $_w_dbUser, $_w_dbPass, $_w_dbName, $_w_dbPrefix);

function panic($text = 'noname error')
{
   die('<div style="position: absolute;
        z-index: 999;
        top: 0;
        left: 0;
        background: #fff;
        width: 100%;
        height: 100%;">
        <big>Błąd krytyczny uniemożliwiający kontynuowanie.</big><br>Debug: ' . $text . '</div>');
}

/* Sprawdzamy czy zalogowany
**********************************************/

function AreLoggedIn()
{
   global $_w_superusers;
   if($_SESSION['WTRMLN_ADMIN_LOGGED_IN'] != 'true')
   { 
      return FALSE;
   }
   if(!isset($_w_superusers[$_SESSION['WTRMLN_ADMIN_LOGIN']]))
   {
      return FALSE;
   }
   if($_SESSION['WTRMLN_ADMIN_PASS'] != $_w_superusers[$_SESSION['WTRMLN_ADMIN_LOGIN']])
   {
      return FALSE;
   }
   
   $_db = DB::Instance();
   
   $checkFail = $_db->query("SELECT `fails` FROM `admin_loginfail` WHERE `ip` = '" . ClientIP() . "'");
   $failData = $checkFail->to_obj();
   
   if($failsRows > 0)
   {
      return FALSE;
   }
   
   if(!isset($_SESSION['WTRMLN_ADMIN_STARTTIME']))
   {
      return FALSE;
   }
   
   if($_SESSION['WTRMLN_ADMIN_STARTTIME'] < time() - 600)
   {
      return FALSE;
   }
   
   return TRUE;
}

/* Biblioteki
**********************************************/
include WTRMLN_ADMINLIBS . 'render.php';

include WTRMLN_LIBS . 'url.php';

$_url = new URL('hi');

class AdminModule
{
   function AdminModule()
   {
      $this->render = new Render();
      $this->db = DB::Instance();
   }
}

function getMenu() // jakieś stare coś. 
{
   $menu = file_get_contents('config/topmenu.php');
   $menu = str_replace(array("\r\n", "\r"), "\n", $menu);
   $menu = explode("\n", $menu);
   
   $menu = array_reverse($menu);
   array_pop($menu);
   $menu = array_reverse($menu);
   
   return $menu;
}

?>
