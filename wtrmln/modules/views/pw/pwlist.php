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

<div class="tr">
   <big>
      <a href="$/pw/new">Nowa prywatna wiadomość</a>
   </big>
</div>

<table width="100%">
   <caption>Prywatne wiadomości</caption>
   <tr>
      <th width="20%">
         Nadawca
      </th>
      <th>
         Temat
      </th>
      <th width="20%">
         Wysłany
      </th>
      <th width="50px">
         Opcje
      </th>
   </tr>
   <? while($pw_item = $pwlist->to_obj()){ ?>
      <tr>
         <td class="tc">
            <?=$pw_item->nick ?>
         </td>
         <td>
            <a href="$/pw/view/<?=$pw_item->id ?>">
               <?=$pw_item->subject ?>
            </a>
         </td>
         <td class="tc">
            <?=plDate($pw_item->sent) ?>
         </td>
         <td class="tc">
            <a href="$/pw/delete/<?=$pw_item->id ?>">[Usuń]</a>
         </td>
      </tr>
   <? } ?>
</table>

<div class="tr">
   <big>
      <a href="$/pw/new">Nowa prywatna wiadomość</a>
   </big>
</div>