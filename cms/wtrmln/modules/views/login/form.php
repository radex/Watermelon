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
<form action="$/login/submit" method="POST">
   <div class="dl" style="width:50%">
      <fieldset class="loginform">
         <legend>Zaloguj się</legend>
         
         <label for="login">Wpisz swój login:</label>
         <input type="text" name="login" id="login">
          
         <br><br>
            
         <label for="password">Wpisz swoje hasło:</label>
         <input type="password" name="password" id="password">
         
         <br><br>
         
         <label for="submit">&nbsp;</label>
         <input type="submit" id="submit" value="Loguj!">
         
      </fieldset>
   </div>
   <fieldset>
      <legend>Inne</legend>
      
      <input type="checkbox" name="autologin" id="autologin">
      <label for="autologin">Zaloguj mnie automatycznie przy każdej wizycie</label>
      
      <br><br>
      
      <a href="$/login/sendnewpassword" title="Wysyłanie nowego hasła">Zapomniałem hasła!</a>
      
      <br>
      
      <a href="$/register" title="Rejestracja"><strong>Nie mam jeszcze konta!</strong></a>
      
   </fieldset>
</form>
