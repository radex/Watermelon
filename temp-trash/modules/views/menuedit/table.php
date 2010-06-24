<?php if(!defined('WM_IS')) exit;
 //  
 //  Watermelon CMS
 //  
 //  Copyright 2009 Radosław Pietruszewski.
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

<a href="$/">Panel Admina</a> &gt; Menu

<div class="tr">
   <big>
      <a href="$/menuedit/new/">Nowe menu</a>
   </big>
</div>

Edycja menu:
Głównego |
<a href="$/menuedit/top">Górnego</a> |
<a href="$/menuedit/pa">Panelu Admina</a>

<table>
   <tr>
      <th>Pozycja</th> <th>Nazwa</th> <th>Treść</th> <th>Warunek</th> <th>Opcje</th>
   </tr>
   <list object $menus>
      <tr>
         <td class="tc">
            <a href="$/menuedit/setpos/<$id>/<? echo $position + 1 ?>" title="w dół">&darr;</a>
            <$position>
            <? if($position > 0){ ?><a href="$/menuedit/setpos/<$id>/<? echo $position - 1 ?>" title="w górę">&uarr;</a><? }else{ ?>&nbsp;<? } ?>
         </td>
         <td class="tc">
            <$capt>
         </td>
         <td>
            <?
            $text = htmlspecialchars($content);
            if(strlen($text) > 100)
            {
               echo nl2br(substr($text, 0, 100)) . '...';
            }
            else
            {
               echo nl2br($text);
            }
            ?>
         </td>
         <td class="tc">
            <? echo htmlspecialchars($condition) ?>
         </td>
         <td class="tc">
            <a href="$/menuedit/edit/<$id>">[edytuj]</a>
            <a href="$/menuedit/delete/<$id>">[usuń]</a>
         </td>
      </tr>
   </list>
   <tr>
      <th>Pozycja</th> <th>Nazwa</th> <th>Treść</th> <th>Warunek</th> <th>Opcje</th>
   </tr>
</table>

<div class="tr">
   <big>
      <a href="$/menuedit/new/">Nowe menu</a>
   </big>
</div>

pozycja ostatniego menu: <?php echo Config::getConf('max_menu'); ?>