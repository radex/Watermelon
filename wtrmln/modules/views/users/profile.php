<?php if(!defined('WTRMLN_IS')) exit;
/********************************************************************

  Watermelon CMS

Copyright 2008-2009 Radosław Pietruszewski

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

<div class="userprofile">
   <div class="userprofile_avatar">
   </div>
   <div class="userprofile_sendpw">
      <a href="$/pw/new/<$nick>">Wyślij PW</a>
   </div>
   <h2><$nick></h2>
   <strong>Postów: </strong> <$posts><br>
   <?
      if($lastseen < time() - 500)
      {
         if($lastseen != 0)
         {
            echo '<strong>Ostatnio widziany: </strong>' . plDate($lastseen);
            
            echo '<br>';
         }
         else
         {
            echo '<strong>Ostatnio widziany: </strong> nigdy <br>';
         }
      }
      else
      {
         echo '<strong>Ostatnio widziany: </strong> online <br>';
      }
   ?>
</div>