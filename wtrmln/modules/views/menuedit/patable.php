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

<a href="$/">Panel Admina</a> &gt; <a href="$/menuedit/">Menu</a> &gt; Panel Admina

<br><br>

Edycja menu:
<a href="$/menuedit">Głównego</a> |
<a href="$/menuedit/top">Górnego</a> |
Panelu Admina

<table>
   <tr>
      <th width="25%">Nazwa</th> <th width="50%">Opis</th> <th width="25%">Opcje</th>
   </tr>
   <foreach $menus as $menu>
      <?php list($menuName, $menuTitle) = $menu; ?>
      <tr>
         <td>
            <$menuName>
         </td>
         <td>
            <$menuTitle>
         </td>
         <td>
            <? if($i != $j){ ?> <a href="$/menuedit/pa_down/<$menuName>">[Na dół]</a> <? } ?>
            <? if($i != 0){ ?> <a href="$/menuedit/pa_up/<$menuName>">[W górę]</a> <? } ?>
         </td>
      </tr>
      <? $i++; ?>
   </foreach>
   <tr>
      <th>Nazwa</th> <th>Opis</th> <th>Opcje</th>
   </tr>
</table>