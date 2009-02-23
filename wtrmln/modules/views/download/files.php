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
?>

<table width="100%">
   <tr>
      <th>ID</th> <th>Plik</th> <th>Opis</th> <th>Wielkość</th> <th>Dodany</th> <th>Ściągnięć</th>
   </tr>
   <list object $files>
      <tr>
         <td><$id></td>
         <td><a href="<$url>"><$file></a></td>
         <td><$description></td>
         <td><$size></td>
         <td><date $date></td>
         <td><$downloads></td>
      </tr>
   </list>
   <tr>
      <th>ID</th> <th>Plik</th> <th>Opis</th> <th>Wielkość</th> <th>Dodany</th> <th>Ściągnięć</th>
   </tr>
</table>