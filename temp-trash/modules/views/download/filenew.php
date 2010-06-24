<?php if(!defined('WM_IS')) exit;
 //  
 //  Watermelon CMS
 //  
 //  Copyright 2008-2009 Radosław Pietruszewski.
 //  
 //  This program is free software: you can redistribute it and/or modify
 //  it under the terms of the GNU General Public License as published by
 //  the Free Software Foundation, either version 3 of the License, or
 //  (at your option) any later version.
 //  
 //  This program is distributed in the hope that it will be useful,
 //  but WITHOUT ANY WARRANTY; without even the implied warranty of
 //  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 //  GNU General Public License for more details.
 //  
 //  You should have received a copy of the GNU General Public License
 //  along with this program.  If not, see <http://www.gnu.org/licenses/>.
 //  
?>

<a href="$/">Panel Admina</a> &gt; <a href="$/download">Download</a> &gt; Pliki &gt; Nowy plik

<?php
   Controller::addMeta(
   '<style type="text/css">.newdownloadfile_box label{float:left;width:100px;display:block}'.
   '.newdownloadfile_box #file, .newdownloadfile_box #link, .newdownloadfile_box #description{width:60%}</style>');
?>

<form action="$/download/postfile/<$tkey>/<$tvalue>/<$id>" method="POST">
   <fieldset class="newdownloadfile_box">
      <legend>Nowy plik</legend>
      
      <label for="file">Plik:</label>
      <input type="text" name="file" id="file">
      
      <br>
      
      <label for="link">Link:</label>
      <input type="url" name="link" id="link">
      
      <br>
      
      <label for="description">Opis:</label>
      <input type="text" name="description" id="description">
      
      <br>
      
      <label for="size">Wielkość:</label>
      <input type="number" step="0.1" name="size" id="size" value="1">
      
      <select name="unit">
         <option>B</option>
         <option>KB</option>
         <option selected>MB</option>
         <option>GB</option>
      </select>
      
      <br>
      
      <input type="submit" id="submit" value="Wyślij!">

   </fieldset>
</form>