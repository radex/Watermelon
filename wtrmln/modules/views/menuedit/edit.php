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

<a href="$/">Panel Admina</a> &gt; <a href="$/menuedit">Menu</a> &gt; Edycja

<?php
   Controller::addMeta(
   '<style type="text/css">.editmenu_box label{float:left;width:100px;display:block}'.
   '.editmenu_box #condition, .editmenu_box #name{width:60%}'.
   '.editmenu_box #text{width: 100%; height:250px;}</style>');
?>
<unpack $data>
<form action="$/menuedit/editsubmit/<$tkey>/<$tvalue>/<$id>" method="POST">
   <fieldset class="editmenu_box">
      <legend>Edycja menu</legend>
      
      <label for="name">Nazwa:</label>
      <input type="text" name="name" id="name" value="<$capt>">
      
      <br>
      
      <label for="condition">Warunek:</label>
      <input type="text" name="condition" id="condition" value="<$condition>">
      
      <br>
      
      <label for="text">Treść:</label><br>
      
      <textarea name="text" id="text"><?=htmlspecialchars($content)?></textarea>
      
      <br>
      
      <input type="submit" id="submit" value="Wyślij!">

   </fieldset>
</form>