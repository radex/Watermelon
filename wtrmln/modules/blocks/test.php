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

class BlockTest extends Block
{
   function block()
   {
      ?>
      <div style="margin:10px;background:#eee;border:1px solid #bbb">
      <div style="background:#ddd;border-bottom:1px solid #bbb;font-weight:bold;padding:3px">Najnowsze newsy</div>
      <div style="padding:3px">
      <?php
      
      $news = model('news')->GetNews();
      
      while($newsItem = $news->to_obj())
      {
         $newsArray[] = $newsItem->title;
      }
      
      echo implode('<div style="margin:5px;background:#ccc;height:1px"></div>', $newsArray);
      
      ?>
      </div>
      </div>
      <?php
   }
}

?>