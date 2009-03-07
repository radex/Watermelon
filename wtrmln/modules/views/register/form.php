<?php if(!defined('WTRMLN_IS')) exit;
/********************************************************************

  Watermelon CMS

Copyright 2009 Radosław Pietruszewski

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

<form action="$/register/submit" method="POST">
   <fieldset class="registerform">
      <legend>Rejestracja</legend>

      <label for="login">Wpisz swój login:</label>
      <input type="text" name="login" id="login">

      <br>

      <label for="password">Wpisz swoje hasło:</label>
      <input type="password" name="password" id="password">
      
      <br>

      <label for="password">Powtórz hasło:</label>
      <input type="password" name="password2" id="password2">

      <br>

      <label for="submit">&nbsp;</label>
      <input type="submit" id="submit" value="Rejstruj!">

   </fieldset>
</form>
