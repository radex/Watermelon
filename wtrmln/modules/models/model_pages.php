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

class Model_Pages extends Model
{
   public function Model_Pages()
   {
      parent::Model();
   }

   /*
    * public DBresult GetData(string $pagename)
    *
    * zwraca dane page'a o nazwie $pagename
    */

   public function GetData($pagename)
   {
      $pagename = mysql_real_escape_string($pagename);

      return $this->db->query("SELECT `content`, `title` FROM `__pages` WHERE `name` = '%1'", $pagename);
   }
}
?>