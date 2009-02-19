<?php
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

class BlockKoniecSzkoły extends Block
{
   function block()
   {
      $send  = mktime(0,0,0,6,20,2009);
      $diff  = $send - time();
      $diff /= 3600;
      $diff /= 24;
      $diff  = (int) $diff;
      
      echo 'do końca szkoły jeszcze tylko <strong>' . $diff . '</strong> dni';
   }
}

?>