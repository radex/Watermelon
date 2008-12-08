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

########################################
define('WTRMLN_IS','true');#############
$_w_autoload=array();###################
$_w_superusers=array();#################
########################################
#              Konfiguracja            #
########################################

/*
 * baseURL
 * 
 * Ścieżka do Watermelon CMS-a
 * 
 * np.: 'http://mojastrona.pl/'
 */

$_w_baseURL    = '';

/*
 * siteURL
 * 
 * Ścieżki do podstron
 * Jeżeli plik .htaccess nie był edytowany, to
 * na 90% będzie to baseURL + index.php/
 * Slash na końcu ("/") jest BARDZO WAŻNY.
 * 
 * np.: 'http://mojastrona.pl/index.php/'
 */

$_w_siteURL    = '';

/*
 * cmsDir
 * 
 * Nazwa głównego katalogu Watermelon CMS-a
 * Jeżeli nazwa katalogu wtrmln/ nie została
 * zmieniona, to będzie to 'wtrmln/'
 */

$_w_cmsDir     = 'wtrmln/';

/*
 * dbHost
 * 
 * Serwer bazy danych MySQL.
 * Na 99% będzie to 'localhost'.
 */

$_w_dbHost     = 'localhost';

/*
 * dbUser
 * 
 * Nazwa użytkownika bazy danych
 * Powinieneś/powinnaś go znać.
 * Jeżeli instalujesz Watermelon CMS-a
 * na serwerze lokalnym to najprawdopodobniej
 * będzie to root. 
 */

$_w_dbUser     = '';

/*
 * dbPass
 * 
 * Hasło użytkownika bazy danych
 */

$_w_dbPass     = '';

/*
 * dbName
 * 
 * Nazwa bazy danych, do której
 * importowałeś(aś) zrzut bazy danych
 * dołączony do Watermelon CMS-a.
 */

$_w_dbName     = '';

/*
 * dbPrefix
 * 
 * Prefiks nazw tabel w bazie danych.
 * Jeśli importowałeś(aś) zrzut bazy
 * danych i nie zmieniałeś(aś) nazw
 * tabel, zostaw pole puste.
 */

$_w_dbPrefix   = '';

/*
 * siteName
 * 
 * Nazwa strony
 * 
 * np.: 'Strona Jasia Śmietany'
 */

$_w_siteName   = '';

/*
 * siteSlogan
 * 
 * Slogan strony / jakaś nazwa
 * opisująca stronę
 * 
 * np.: 'Bes sęsu'
 */

$_w_siteSlogan = '';

/*
 * theme
 * 
 * Szablon (wygląd, skórka) strony.
 * Jeśli nie zostały wgrane żadne
 * dodatkowe szablony to będzie to
 * 'wcmslay'
 * 
 * np.: 'wcmslay'
 */

$_w_theme      = 'wcmslay';

/*
 * defaultCnt
 * 
 * Domyślny kontroler. Nie zmieniaj,
 * jeśli nie jesteś pewien tego co 
 * robisz.
 * 
 * np.: 'test'
 */

$_w_defaultCnt = 'test';

/*
 * hashAlgo
 * 
 * Lista używanych algorytmów haszowania
 * haseł, saltów itp. Nie zmieniaj, jeśli
 * nie jesteś pewien tego co robisz.
 * 
 * np.: array('_sha1')
 */

$_w_hashAlgo   = array('_sha1');

/*
 * dHashAlgo
 * 
 * Domyślny algorytm haszowania. Nie zmieniaj,
 * jeśli nie jesteś pewien tego co robisz.
 * 
 * np.: 0
 */

$_w_dHashAlgo  = 0;

/*
 * autoload
 * 
 * Lista automatycznie wczytywanych
 * pluginów. Nie zmieniaj, jeśli nie
 * jesteś pewien tego co robisz.
 * 
 * np.: array('user', '')
 */

$_w_autoload[] = array('user', '');

/*
 * metaSrc
 * 
 * Lista elementów (tagów), które
 * mają być w <head>, np. słowa
 * kluczowe, ustawienia DublinCore
 * itp. Każdy tag to jeden element
 * tablicy!
 * 
 * np.: array('<meta costam>', '<meta costam innego>')
 */

$_w_metaSrc =
	array(

	);
	
/*
 * superusers
 * 
 * Nie zmieniać!
 */

$_w_superusers[''] = '';

########################################
#      Systemowe -  NIE EDYTOWAĆ!      #
########################################

$_w_basePath = str_replace('\\', '/', realpath(dirname(__FILE__))) . '/';

define('WTRMLN_SITEURL'      , $_w_siteURL                                    );
define('WTRMLN_CMSDIR'       , $_w_cmsDir                                     );
define('WTRMLN_THEME'        , $_w_theme                                      );
define('WTRMLN_DEFAULTCNT'   , $_w_defaultCnt                                 );
define('WTRMLN_SITENAME'     , $_w_siteName                                   );
define('WTRMLN_SITESLOGAN'   , $_w_siteSlogan                                 );

define('WTRMLN_CMSURL'       , $_w_baseURL    . WTRMLN_CMSDIR                 );
define('WTRMLN_CMSPATH'      , $_w_basePath   . WTRMLN_CMSDIR                 );
define('WTRMLN_APPPATH'      , WTRMLN_CMSPATH . 'modules/'                    );

define('WTRMLN_THEMEURL'     , WTRMLN_CMSURL  . 'themes/' . WTRMLN_THEME . '/');
define('WTRMLN_THEMEPATH'    , WTRMLN_CMSPATH . 'themes/' . WTRMLN_THEME . '/');
define('WTRMLN_LIBS'         , WTRMLN_CMSPATH . 'libs/'                       );
define('WTRMLN_ADMINLIBS'    , WTRMLN_CMSPATH . 'admin/libs/'                 );
define('WTRMLN_HELPERS'      , WTRMLN_CMSPATH . 'helpers/'                    );
define('WTRMLN_FILES'        , WTRMLN_CMSURL  . 'files/'                      );
define('WTRMLN_ADMIN'        , WTRMLN_CMSURL  . 'admin/'                      );
define('WTRMLN_ADMINCNT'     , WTRMLN_APPPATH . 'admin/'                      );
define('WTRMLN_CONTROLLERS'  , WTRMLN_APPPATH . 'controllers/'                );
define('WTRMLN_VIEWS'        , WTRMLN_APPPATH . 'views/'                      );
define('WTRMLN_MODELS'       , WTRMLN_APPPATH . 'models/'                     );
define('WTRMLN_PLUGINS'      , WTRMLN_APPPATH . 'plugins/'                    );

include WTRMLN_LIBS . 'config.php';

Config::$theme               = $_w_theme;
Config::$defaultController   = $_w_defaultCnt;
Config::$hashAlgo            = $_w_hashAlgo;
Config::$defaultHashAlgo     = $_w_dHashAlgo;
Config::$siteName            = $_w_siteName;
Config::$siteSlogan          = $_w_siteSlogan;

?>
