<?php if(!defined('WTRMLN_IS')) exit;
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

<a href="$/">Panel Admina</a> &gt; <a href="$/download">Download</a> &gt; Nowa grupa

<?php
   Controller::addMeta(
   '<style type="text/css">.newdownloadgroup_box label{float:left;width:100px;display:block}'.
   '.newdownloadgroup_box #name{width:60%}'.
   '.newdownloadgroup_box #description{width: 100%; height:250px;}</style>');
?>

<form action="$/download/postgroup/<$tkey>/<$tvalue>" method="POST">
   <fieldset class="newdownloadgroup_box">
      <legend>Nowa grupa plików</legend>
      
      <label for="name">Nazwa:</label>
      <input type="text" name="name" id="name">
      
      <br>
      
      <label for="description">Opis:</label><br>
      
      <textarea name="description" id="description"></textarea>
      
      <br>
      
      <input type="submit" id="submit" value="Wyślij!">

   </fieldset>
</form>