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

class Model_TempKeys extends Model
{
   public function Model_TempKeys()
   {
      parent::Model();
   }
   
   public function MakeKey($key, $value, $comment)
   {
      $key = mysql_real_escape_string($key);
      $value = mysql_real_escape_string($value);
      $comment = mysql_real_escape_string($comment);
      
      $this->db->query("INSERT INTO `__temporary_keys` (`key`, `value`, `created`, `comment`) VALUES ('%1', '%2', '%3', '%4')", $key, $value, time(), $comment);
      
      $this->DeleteOldKeys();
   }
   
   public function GetKey($key)
   {
      $key = mysql_real_escape_string($key);
      
      return $this->db->query("SELECT * FROM `__temporary_keys` WHERE `key` = '%1'", $key);
   }
   
   public function DeleteKey($key)
   {
      $key = mysql_real_escape_string($key);
      
      $this->db->query("DELETE FROM `__temporary_keys` WHERE `key` = '%1'", $key);
   }
   
   private function DeleteOldKeys()
   {
      $this->db->query("DELETE FROM `__temporary_keys` WHERE `created` < '%1'", time() - 300);
   }
}
?>