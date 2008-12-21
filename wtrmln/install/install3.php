<?php
ob_start();
if(file_exists('install_lock'))
{
   header('Location: locked.php');
   exit;
}

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

$baseURL    = $_POST['baseURL'];
$siteURL    = $_POST['siteURL'];
$cmsDir     = $_POST['cmsDir'];
$dbHost     = $_POST['dbHost'];
$dbUser     = $_POST['dbUser'];
$dbPass     = $_POST['dbPass'];
$dbName     = $_POST['dbName'];
$dbPrefix   = $_POST['dbPrefix'];
$siteName   = $_POST['siteName'];
$siteSlogan = $_POST['siteSlogan'];
$theme      = $_POST['theme'];
$defaultCnt = $_POST['defaultCnt'];
$hashAlgo   = $_POST['hashAlgo'];
$dHashAlgo  = $_POST['dHashAlgo'];
$autoload   = $_POST['autoload'];
$metaSrc    = $_POST['metaSrc'];
/**
var_dump($baseURL,
$siteURL,
$cmsDir ,
$dbHost ,
$dbUser ,
$dbPass  ,   
$dbName   ,  
$dbPrefix  , 
$siteName   ,
$siteSlogan ,
$theme      ,
$defaultCnt ,
$hashAlgo   ,
$dHashAlgo  ,
$autoload  ,
$metaSrc);
   */

if(@mysql_connect($dbHost, $dbUser, $dbPass))
{
   $connect = true;
}
else
{
   $connect = false;
}
?>
<h2>Instalacja - krok 3</h2>

<div class="box_i">
<strong>Krok 3</strong>
W tym kroku nastąpi ostateczna weryfikacja danych i zapis konfiguracji.
</div>

<h3>Weryfikacja danych</h3>
<p>
Upewnij się, czy poniższe dane są na pewno prawidłowe.
</p>
<table>
   <tr>
      <th>Główna ścieżka</th>
      <td><?php echo $baseURL ?></td>
   </tr>
   <tr>
      <th>Ścieżka index.php*</th>
      <td><?php echo $siteURL ?></td>
   </tr>
   <tr>
      <th>Główny folder*</th>
      <td><?php echo $cmsDir ?></td>
   </tr>
   <tr>
      <th>Host bazy danych</th>
      <td><?php echo $dbHost ?></td>
   </tr>
   <tr>
      <th>Użytkownik bazy danych</th>
      <td><?php echo $dbUser ?></td>
   </tr>
   <tr>
      <th>Hasło bazy danych</th>
      <td><?php echo $dbPass[0] . str_repeat('*', strlen($dbPass)-2) . $dbPass[strlen($dbPass)-1] ?></td>
   </tr>
   <tr>
      <th>Prefiks tabel*</th>
      <td><?php echo $dbPrefix ?></td>
   </tr>
   <tr>
      <th>Nazwa strony</th>
      <td><?php echo $siteName ?></td>
   </tr>
   <tr>
      <th>Slogan strony</th>
      <td><?php echo $siteSlogan ?></td>
   </tr>
   <tr>
      <th>Layout*</th>
      <td><?php echo $theme ?></td>
   </tr>
   <tr>
      <th>Domyślny kontroler*</th>
      <td><?php echo $defaultCnt ?></td>
   </tr>
   <tr>
      <th>Algorytmy haszowania*</th>
      <td><?php echo $hashAlgo ?></td>
   </tr>
   <tr>
      <th>Domyślny algorytm haszowania*</th>
      <td><?php echo $dHashAlgo ?></td>
   </tr>
</table>

* - tylko dla doświadczonych

<?php

if($connect === true)
{
   echo '<div class="box_c">Połączenie z bazą danych <strong style="display:inline">udane</strong></div>';
}
else
{
   echo '<div class="box_e">Połączenie z bazą danych <strong style="display:inline">nieudane</strong></div>';
}

?>

<h3>Zapis konfiguracji</h3>

<?php
/*
<ul>
   <li>Otwórz swój ulubiony edytor tekstu (np. Notatnik, notepad++, gedit, KWrite itd.)</li>
   <li>Wklej w nim kod podany pod tą listą</li>
   <li>Zapisz plik gdziekolwiek na swoim komputerze (np. na pulpicie) pod nazwą <em>config.php</em></li>
   <li>Wyślij zapisany przed chwilą plik <em>config.php</em> na swój serwer do głównego folderu z Watermelon CMS-em (tak, żeby znajdowałał się pod adresem <em><?php echo $baseURL ?>config.php</em>)</li>
</ul>

<textarea rows="10" cols="30" id="configSrc" style="margin-bottom:5px;">
*/

$metaSrc = explode("\n",str_replace(array("\r\n", "\r"), "\n", $metaSrc));

foreach($metaSrc as $var)
{
   $metaSrc2[] = "'" . $var . "'";
}

$metaSrc = implode(',', $metaSrc2);

$autoload = implode(',', explode("\n",str_replace(array("\r\n", "\r"), "\n", $autoload)));

$config = '<?php
########################################
define(\'WTRMLN_IS\',\'true\');#############
$_w_autoload=array();###################
$_w_superusers=array();#################
########################################
#              Konfiguracja            #
########################################
$_w_baseURL    = \'' . $baseURL . '\';
$_w_siteURL    = \'' . $siteURL . '\';
$_w_cmsDir     = \'' . $cmsDir . '\';
$_w_dbHost     = \'' . $dbHost . '\';
$_w_dbUser     = \'' . $dbUser . '\';
$_w_dbPass     = \'' . $dbPass . '\';
$_w_dbName     = \'' . $dbName . '\';
$_w_dbPrefix   = \'' . $dbPrefix . '\';
$_w_siteName   = \'' . $siteName . '\';
$_w_siteSlogan = \'' . $siteSlogan . '\';
$_w_theme      = \'' . $theme . '\';
$_w_defaultCnt = \'' . $defaultCnt . '\';
$_w_hashAlgo   = array(' . $hashAlgo . ');
$_w_dHashAlgo  = ' . intval($dHashAlgo) . ';
$_w_autoload   = array(' . $autoload . ');
$_w_metaSrc    = array();
$_w_superusers[\'\'] = \'\';
########################################
#      Systemowe -  NIE EDYTOWAĆ!      #
########################################
$_w_basePath = str_replace(\'\\\\\', \'/\', realpath(dirname(__FILE__))) . \'/\';

define(\'WTRMLN_BASEURL\'      , $_w_baseURL                                    );
define(\'WTRMLN_SITEURL\'      , $_w_siteURL                                    );
define(\'WTRMLN_CMSDIR\'       , $_w_cmsDir                                     );
define(\'WTRMLN_THEME\'        , $_w_theme                                      );
define(\'WTRMLN_DEFAULTCNT\'   , $_w_defaultCnt                                 );
define(\'WTRMLN_SITENAME\'     , $_w_siteName                                   );
define(\'WTRMLN_SITESLOGAN\'   , $_w_siteSlogan                                 );

define(\'WTRMLN_CMSURL\'       , $_w_baseURL    . WTRMLN_CMSDIR                 );
define(\'WTRMLN_CMSPATH\'      , $_w_basePath   . WTRMLN_CMSDIR                 );
define(\'WTRMLN_APPPATH\'      , WTRMLN_CMSPATH . \'modules/\'                    );

define(\'WTRMLN_THEMEURL\'     , WTRMLN_CMSURL  . \'themes/\' . WTRMLN_THEME . \'/\');
define(\'WTRMLN_THEMEPATH\'    , WTRMLN_CMSPATH . \'themes/\' . WTRMLN_THEME . \'/\');
define(\'WTRMLN_LIBS\'         , WTRMLN_CMSPATH . \'libs/\'                       );
define(\'WTRMLN_ADMINLIBS\'    , WTRMLN_CMSPATH . \'admin/libs/\'                 );
define(\'WTRMLN_HELPERS\'      , WTRMLN_CMSPATH . \'helpers/\'                    );
define(\'WTRMLN_FILES\'        , WTRMLN_CMSURL  . \'files/\'                      );
define(\'WTRMLN_ADMIN\'        , WTRMLN_CMSURL  . \'admin/\'                      );
define(\'WTRMLN_ADMINCNT\'     , WTRMLN_APPPATH . \'admin/\'                      );
define(\'WTRMLN_CONTROLLERS\'  , WTRMLN_APPPATH . \'controllers/\'                );
define(\'WTRMLN_VIEWS\'        , WTRMLN_APPPATH . \'views/\'                      );
define(\'WTRMLN_MODELS\'       , WTRMLN_APPPATH . \'models/\'                     );
define(\'WTRMLN_PLUGINS\'      , WTRMLN_APPPATH . \'plugins/\'                    );

include WTRMLN_LIBS . \'config.php\';

Config::$theme               = $_w_theme;
Config::$defaultController   = $_w_defaultCnt;
Config::$hashAlgo            = $_w_hashAlgo;
Config::$defaultHashAlgo     = $_w_dHashAlgo;
Config::$siteName            = $_w_siteName;
Config::$siteSlogan          = $_w_siteSlogan;
?>';
/*
</textarea>

<button type="button" onClick="document.getElementById('configSrc').focus();document.getElementById('configSrc').select();document.getElementById('selectBtn').innerHTML = 'A teraz przyciśnij Ctrl + C'" id="selectBtn">Zaznacz</button>
*/

if(!$fp = @fopen('../../config.php', 'w'))
{
   echo '<div class="box_e">Zapis konfiguracji <strong style="display:inline">nieudany</strong></div>';
}
else
{
   echo '<div class="box_c">Zapis konfiguracji <strong style="display:inline">udany</strong></div>';
   fwrite($fp, $config);
   fclose($fp);
}

?>

<br><br><br>

<div class="dr">
(Krok 4) <big><a href="install4.php">Dalej</a></big>
</div>

<big><a href="install2.php">Wstecz</a></big> (Krok 2)
<?php
   include 'layout.php';
?>