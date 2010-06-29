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
<form action="$/login/sendnewpasswordsubmit" method="POST">
   <fieldset class="sendpassform">
      <legend>Odzyskaj hasło</legend>

      <label for="login">Wpisz swój login:</label>
      <input type="text" name="login" id="login">

      <br>

      <label for="email">ALBO wpisz swój e-mail:</label>
      <input type="email" name="email" id="email">

      <br>

      <label for="submit">&nbsp;</label>
      <input type="submit" id="submit" value="Loguj!">

   </fieldset>
</form>
