<?php if(!defined('WTRMLN_IS')) exit;
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
   wysłany <?=plDate($sent) ?>
   </div>
   <div class="postcontent">
   <$text>
   </div>
</div>

<div class="dr">
   <big>
      <a href="$/pw/delete/<$id>">Usuń</a> <a href="$/pw/response/<$id>">Odpowiedz</a>
   </big>
</div>

<a href="$/pw">Powróć do listy prywatnych wiadomości</a>