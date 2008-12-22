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
      <input type="text" name="addressee" id="addressee" value="<$nick>">
      
      <br>
      
      <label for="subject">Temat:</label>
      <input type="text" name="subject" id="subject" value="Re:<$subject>">
      
      <br>
      
      <label for="text">Treść:</label><br>
      
      <textarea name="text" id="text">[quote="<$nick>"]
<$text>
[/quote]</textarea>
      <br>
      
      <input type="submit" id="submit" value="Wyślij!">

   </fieldset>
</form>