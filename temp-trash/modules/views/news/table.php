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

<a href="$/">Panel Admina</a> &gt; Newsy

<div class="tr">
   <big>
      <a href="$/news/new/">Nowy news</a>
   </big>
</div>

<table>
<tr>
   <th>
      Tytuł
   </th>
   <th>
      Napisany
   </th>
   <th>
      Autor
   </th>
   <th width="50">
      Opcje
   </th>
</tr>
<list object $newsList>
<tr>
   <td>
      <h3><a href="<?=WM_MAINURL?>news#news_<$id>"><$title></a></h3>
      <br>
      <?
      $text = strip_tags($text);
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
      <date $date>
   </td>
   <td class="tc">
      <nick $author>
   </td>
   <td class="tc">
      <a href="$/news/edit/<$id>">[edytuj]</a> 
      <a href="$/news/delete/<$id>">[usuń]</a> 
   </td>
</tr>
</list>
<tr>
   <th>
      Tytuł
   </th>
   <th>
      Napisany
   </th>
   <th>
      Autor
   </th>
   <th>
      Opcje
   </th>
</tr>
</table>

<div class="tr">
   <big>
      <a href="$/news/new/">Nowy news</a>
   </big>
</div>