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

/*
 * dla 100% bezpieczeństwa, np. dla ostatecznego systemu na serwer
 */

//error_reporting(0);

/*
 * dla zwykłych userów, developerów
 */

//error_reporting(E_ALL ^ E_NOTICE);

/*
 * dla developerów [pedantic mode]
 */

error_reporting(E_ALL);

/*
 * dla developerów [superpedantic mode]
 */

//error_reporting(E_ALL | E_STRICT);

/*
 * dla różnych testów itp
 */

//define('NOMENU', '');

/*
 * dla developerów
 */

define('DEBUG', '');

// wyłączemy syf o nazwie "magic quotes"

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

include 'config.php';

include WTRMLN_CMSPATH . 'system.php';

?>
