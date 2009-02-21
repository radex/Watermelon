<?php if(!defined('WTRMLN_IS')) exit;
/********************************************************************

  Watermelon CMS

Copyright 2008-2009 Radosław Pietruszewski

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
?>

<a href="$/">Panel Admina</a> &gt; <a href="$/menuedit">Menu</a> &gt; <a href="$/menuedit/top">Górne</a> &gt; Edycja

<?php
   Controller::addMeta(
   '<style type="text/css">.edittopmenu_box label{float:left;width:220px;display:block}'.
   '.edittopmenu_box #name, .edittopmenu_box #condition, .edittopmenu_box #link{width:60%}</style>');
?>

<form action="$/menuedit/topeditsubmit/<$tkey>/<$tvalue>/<$id>" method="POST">
   <fieldset class="edittopmenu_box">
      <legend>Edycja górnego menu</legend>
      
      <label for="name">Nazwa:</label>
      <input type="text" name="name" id="name" value="<$name>">
      
      <br>
      
      <label for="link">Link:</label>
      <input type="text" name="link" id="link" value="<$link>">
      
      <br>
      
      <label for="condition">Oznaczone jako aktualne gdy:</label>
      <input type="text" name="condition" id="condition" value="<$condition>">
      
      <br>
      
      <input type="submit" id="submit" value="Wyślij!">
   </fieldset>
</form>