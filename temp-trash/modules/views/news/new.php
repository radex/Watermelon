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

<a href="$/">Panel Admina</a> &gt; <a href="$/news">Newsy</a> &gt; Nowy

<?php
   Controller::addMeta(
   '<style type="text/css">.newnews_box label{float:left;width:100px;display:block}'.
   '.newnews_box #title{width:60%}'.
   '.newnews_box #text{width: 100%; height:250px;}</style>');
?>

<form action="$/news/post/<$tkey>/<$tvalue>" method="POST">
   <fieldset class="newnews_box">
      <legend>Nowy news</legend>
      
      <label for="title">Temat:</label>
      <input type="text" name="title" id="title">
      
      <br>
      
      <label for="text">Treść:</label><br>
      
      <textarea name="text" id="text"></textarea>
      
      <br>
      
      <input type="submit" id="submit" value="Wyślij!">

   </fieldset>
</form>