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

include 'commons.php';
if(!AreLoggedIn()){ header("Location: login.php"); exit; }

////////////////

// zamieniamy _ na /, tak aby można było robić kontrolery w podfolderach

$_w_controllerPath = str_replace('_', '/', $_url->class);

$_w_controllerPath = WTRMLN_ADMINCNT . $_w_controllerPath . '.php';

if(file_exists($_w_controllerPath))
{
   include $_w_controllerPath;
}
else
{
   panic('Nie moge znalesc pliku podanego controllera');
}

if(class_exists($_url->class))
{
   $_controller = new $_url->class();
}
else
{
   panic('Nie moge znalesc klasy podanego controllera');
}

if(!method_exists($_controller, $_url->method))
{
   panic('Nie moge znalesc podanej funkcji składowej controllera');
}

// przystepujemy do roboty

$_controller->{$_url->method}();

$content = ob_get_contents();
@ob_end_clean();

// w łatwy sposób umożliwiamy tworzenie ścieżek do konkretnego modułu

$content = str_replace('<a href="$/', '<a href="' . WTRMLN_ADMIN . 'index.php/', $content);

$menu =
'<div id="menu">
<div>
   <a href="#">Elo</a>
   <a href="#">Elo</a>
   <a href="#" class="current">Elo</a>
   <a href="#">Elo</a>
   <br>
</div>
</div>
<div id="submenu">
<div>
   <a href="#">Elo</a>
   <a href="#">Elo</a>
   <a href="#" class="current">Elo</a>
   <a href="#">Elo</a>
   <br>
</div>
</div>';

$menua =
array(
   '3',
   '1', '#1',
   array(
      '3',
      '1a', '#1a',
      '1b', '#1b',
      '1c', '#1c',
      '1d', '#1d',
   ),
   '2', '#2',
   array(
      '3',
      '2a', '#2a',
      '2b', '#2b',
      '2c', '#2c',
      '2d', '#2d',
   ),
   '3', '#3',
   array(
      '3',
      '3a', '#3a',
      '3b', '#3b',
      '3c', '#3c',
      '3d', '#3d',
   ),
   '4', '#4',
   array(
      '3',
      '4a', '#4a',
      '4b', '#4b',
      '4c', '#4c',
      '4d', '#4d',
   )
);



include 'themes/' . WTRMLN_ADMIN_THEME . '/index.php';

?>
