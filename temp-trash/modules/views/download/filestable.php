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

<a href="$/">Panel Admina</a> &gt; <a href="$/download">Download</a> &gt; Pliki

<div class="tr">
   <big>
      <a href="$/download/newfile/<$gid>">Nowy plik</a>
   </big>
</div>

<table>
   <tr>
      <th>ID</th> <th>Plik</th> <th>Opis</th> <th>Wielkość</th> <th>Dodany</th> <th>Ściągnięć</th> <th>Opcje</th>
   </tr>
   <list object $files>
      <tr>
         <td><$id></td>
         <td><a href="<$url>"><$file></a></td>
         <td><$description></td>
         <td><$size></td>
         <td><date $date></td>
         <td><$downloads></td>
         <td>
            <a href="$/download/editfile/<$id>">[Edytuj]</a>
            <a href="$/download/deletefile/<$id>">[Usuń]</a>
         </td>
      </tr>
   </list>
   <tr>
      <th>ID</th> <th>Plik</th> <th>Opis</th> <th>Wielkość</th> <th>Dodany</th> <th>Ściągnięć</th> <th>Opcje</th>
   </tr>
</table>

<div class="tr">
   <big>
      <a href="$/download/newfile/<$gid>">Nowy plik</a>
   </big>
</div>