<?php if(!defined('WM_IS')) exit;
 //  
 //  This file is part of Watermelon CMS
 //  
 //  Copyright 2008 Radosław Pietruszewski.
 //  
 //  Watermelon CMS is free software: you can redistribute it and/or modify
 //  it under the terms of the GNU General Public License as published by
 //  the Free Software Foundation, either version 3 of the License, or
 //  (at your option) any later version.
 //  
 //  Watermelon CMS is distributed in the hope that it will be useful,
 //  but WITHOUT ANY WARRANTY; without even the implied warranty of
 //  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 //  GNU General Public License for more details.
 //  
 //  You should have received a copy of the GNU General Public License
 //  along with Watermelon CMS. If not, see <http://www.gnu.org/licenses/>.
 //  
?>

<?php
   Controller::addMeta(
   '<style type="text/css">.newpw_box label{float:left;width:100px;display:block}'.
   '.newpw_box #addressee, .newpw_box #subject{width:60%}'.
   '.newpw_box #text{width: 100%; height:250px;}</style>');
?>

<form action="$/pw/send" method="POST">
   <fieldset class="newpw_box">
      <legend>Nowa prywatna wiadomość</legend>
      
      <label for="addressee">Adresat:</label>
      <input type="text" name="addressee" id="addressee"<? echo (!empty($adressee)) ? ' value="' . $adressee . '"' : '' ?>>
      
      <br>
      
      <label for="subject">Temat:</label>
      <input type="text" name="subject" id="subject">
      
      <br>
      
      <label for="text">Treść:</label><br>
      
      <textarea name="text" id="text"></textarea>
      
      <br>
      
      <input type="submit" id="submit" value="Wyślij!">

   </fieldset>
</form>