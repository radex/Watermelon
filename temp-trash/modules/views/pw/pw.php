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

<div class="dr">
   <big>
      <a href="$/pw/delete/<$id>">Usuń</a> <a href="$/pw/response/<$id>">Odpowiedz</a>
   </big>
</div>

<a href="$/pw">Powróć do listy prywatnych wiadomości</a>

<div class="post">
   <div class="posterdata">
      <cite><$nick></cite>
   </div>
   <div class="posttools">
   wysłany <date $sent>
   </div>
   <div class="postcontent">
   <?=bbcode($text)?>
   </div>
</div>

<div class="dr">
   <big>
      <a href="$/pw/delete/<$id>">Usuń</a> <a href="$/pw/response/<$id>">Odpowiedz</a>
   </big>
</div>

<a href="$/pw">Powróć do listy prywatnych wiadomości</a>