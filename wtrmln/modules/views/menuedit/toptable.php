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

$i = 0;
$j = count($menus) - 1;

?>


<a href="$/">Panel Admina</a> &gt; <a href="$/menuedit">Menu</a> &gt; Górne

<div class="tr">
   <big>
      <a href="$/menuedit/topnew/">Nowe menu</a>
   </big>
</div>

Edycja menu:
<a href="$/menuedit">Głównego</a> |
Górnego |
<a href="$/menuedit/pa">Panelu Admina</a>

<table>
   <tr>
      <th>Pozycja</th> <th>Nazwa</th> <th>Link</th> <th>Aktualna gdy</th> <th>Opcje</th>
   </tr>
   <foreach $menus as $menu>
      <tr>
         <td class="tc">
            <? if($i != $j){ ?> <a href="$/menuedit/top_down/<$i>" title="w dół">&darr;</a> <? }else{ ?>&nbsp;<? } ?>
            <?=$i?>
            <? if($i != 0){ ?> <a href="$/menuedit/top_up/<$i>" title="w górę">&uarr;</a> <? }else{ ?>&nbsp;<? } ?>
         </td>
         <td class="tc">
            <?=$menu[0]?>
         </td>
         <td class="tc">
            <a href="<?=WTRMLN_MAINURL . $menu[1]?>">/<?=$menu[1]?></a>
         </td>
         <td class="tc">
            <?=$menu[2]?>
         </td>
         <td class="tc">
            <a href="$/menuedit/topedit/<$i>">[edytuj]</a>
            <a href="$/menuedit/topdelete/<$i>">[usuń]</a> 
         </td>
      </tr>
      <? $i++; ?>
   </foreach>
   <tr>
      <th>Pozycja</th> <th>Nazwa</th> <th>Link</th> <th>Aktualna gdy</th> <th>Opcje</th>
   </tr>
</table>

<div class="tr">
   <big>
      <a href="$/menuedit/topnew/">Nowe menu</a>
   </big>
</div>