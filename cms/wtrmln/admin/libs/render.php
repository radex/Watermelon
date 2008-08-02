<?php
/********************************************************************

  Watermelon CMS

Copyright 2008 Radosław Pietruszewski

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

include 'patables.php';

class Render extends PATables
{
   function header($text)
   {
      echo '<h1>' . $text . '</h1>';
   }
   /**********************************************************/
   /*                         Boksy 
   /**********************************************************/
   function errorBox($text)
   {
      echo '<div class="box_e"><strong>Błąd:</strong>' . $text . '</div>';   
   }
   function warningBox($text)
   {
      echo '<div class="box_w"><strong>Ostrzeżenie:</strong>' . $text . '</div>';   
   }
   function infoBox($text)
   {
      echo '<div class="box_i"><strong>Informacja:</strong>' . $text . '</div>';   
   }
   function confirmBox($text)
   {
      echo '<div class="box_c">' . $text . '</div>';   
   }
   function tipBox($text)
   {
      echo '<div class="box_t"><strong>Wskazówka:</strong>' . $text . '</div>';   
   }
}