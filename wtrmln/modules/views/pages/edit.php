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

<a href="$/">Panel Admina</a> &gt; <a href="$/pages">Strony</a> &gt; Edycja

<?php
   Controller::addMeta(
   '<style type="text/css">.editpage_box label{float:left;width:100px;display:block}'.
   '.editpage_box #title, .editpage_box #name{width:60%}'.
   '.editpage_box #text{width: 100%; height:250px;}</style>');
?>
<unpack $data>
<form action="$/pages/editsubmit/<$tkey>/<$tvalue>/<$id>" method="POST">
   <fieldset class="editpage_box">
      <legend>Edycja strony</legend>
      
      <label for="title">Temat:</label>
      <input type="text" name="title" id="title" value="<$title>">
      
      <br>
      
      <label for="name">Nazwa:</label>
      <input type="text" name="name" id="name" value="<$name>">
      
      <br>
      
      <label for="text">Treść:</label><br>
      
      <textarea name="text" id="text"><?=htmlspecialchars($content)?></textarea>
      
      <br>
      
      <input type="submit" id="submit" value="Wyślij!">

   </fieldset>
</form>