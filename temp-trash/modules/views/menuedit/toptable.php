<?php if(!defined('WM_IS')) exit;
 //  
 //  This file is part of Watermelon CMS
 //  
 //  Copyright 2009 Radosław Pietruszewski.
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
            <a href="<?=WM_MAINURL . $menu[1]?>">/<?=$menu[1]?></a>
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
