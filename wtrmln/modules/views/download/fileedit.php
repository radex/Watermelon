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

<a href="$/">Panel Admina</a> &gt; <a href="$/download">Download</a> &gt; Pliki &gt; Edycja pliku

<?php
   Controller::addMeta(
   '<style type="text/css">.editdownloadfile_box label{float:left;width:100px;display:block}'.
   '.editdownloadfile_box #file, .editdownloadfile_box #link, .newdownloadfile_box #description{width:60%}</style>');
?>
<unpack $data>
<form action="$/download/editfile_submit/<$tkey>/<$tvalue>/<$id>" method="POST">
   <fieldset class="editdownloadfile_box">
      <legend>Nowy plik</legend>
      
      <label for="file">Plik:</label>
      <input type="text" name="file" id="file" value="<$file>">
      
      <br>
      
      <label for="link">Link:</label>
      <input type="url" name="link" id="link" value="<$url>">
      
      <br>
      
      <label for="description">Opis:</label>
      <input type="text" name="description" id="description" value="<$description>">
      
      <br>
      
      <label for="size">Wielkość:</label>
      <input type="number" step="0.1" name="size" id="size" value="<$rsize>">
      
      <select name="unit">
         <option<?=($unit=='B' ? ' selected' : '')?>>B</option>
         <option<?=($unit=='KB' ? ' selected' : '')?>>KB</option>
         <option<?=($unit=='MB' ? ' selected' : '')?>>MB</option>
         <option<?=($unit=='GB' ? ' selected' : '')?>>GB</option>
      </select>
      
      <br>
      
      <input type="submit" id="submit" value="Wyślij!">

   </fieldset>
</form>