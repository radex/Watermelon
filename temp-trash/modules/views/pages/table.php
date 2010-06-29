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
?>

<a href="$/">Panel Admina</a> &gt; Strony

<div class="tr">
   <big>
      <a href="$/pages/new/">Nowa strona</a>
   </big>
</div>

<table>
<tr>
   <th>
      Tytuł
   </th>
   <th width="20%">
      Nazwa
   </th>
   <th width="50">
      Opcje
   </th>
</tr>
<list object $pagesList>
<tr>
   <td>
      <h3><a href="<?=WM_MAINURL?><$name>"><$title></a></h3>
      <br>
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
      <$name>
   </td>
   <td class="tc">
      <a href="$/pages/edit/<$id>">[edytuj]</a> 
      <a href="$/pages/delete/<$id>">[usuń]</a> 
   </td>
</tr>
</list>
<tr>
   <th>
      Tytuł
   </th>
   <th>
      Nazwa
   </th>
   <th width="50">
      Opcje
   </th>
</tr>
</table>

<div class="tr">
   <big>
      <a href="$/pages/new/">Nowa strona</a>
   </big>
</div>