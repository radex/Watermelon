<?php if(!defined('WTRMLN_IS')) exit;
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

<a href="$/">Panel Admina</a> &gt; Download

<div class="tr">
   <big>
      <a href="$/download/newgroup">Nowa grupa</a>
   </big>
</div>

<table>
   <tr>
      <th width="80%">Nazwa</th> <th>Plików</th> <th>Opcje</th>
   </tr>
   <list object $groups>
      <tr>
         <td>
            <h3><a href="$/download/group/<$id>"><$name></a></h3>
            <br>
            <$description>
         </td>
         <td class="tc">
            <$files>
         </td>
         <td class="tc">
            <a href="$/download/editgroup/<$id>">[edytuj]</a><br>
            <a href="$/download/deletegroup/<$id>">[usuń]</a> 
         </td>
      </tr>
   </list>
   <tr>
      <th>Nazwa</th> <th>Plików</th> <th>Opcje</th>
   </tr>
</table>

<div class="tr">
   <big>
      <a href="$/download/newgroup">Nowa grupa</a>
   </big>
</div>