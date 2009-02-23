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

class Model_Download extends Model
{
   /*
    * public DBresult GetGroups()
    * 
    * pobiera grupy plików
    */
   
   public function GetGroups()
   {
      return $this->db->query("SELECT * FROM `__download_groups`");
   }
   
   /*
    * public void PostGroup(string $name, string $description)
    * 
    * tworzy grupę o nazwie $name, z opisem $description
    */
   
   public function PostGroup($name, $description)
   {
      $name = mysql_real_escape_string($name);
      $description = mysql_real_escape_string($description);
      
      $this->db->query("INSERT INTO `__download_groups` (`name`, `description`, `files`) VALUES ('%1', '%2', '0')", $name, $description);
   }
   /*
    * public void DeleteGroup(uint $id)
    * 
    * usuwa grupę plików o ID=$id
    */
   
   public function DeleteGroup($id)
   {
      $id = intval($id);
      
      $this->db->query("DELETE FROM `__download_groups` WHERE `id` = '%1'", $id);
   }
   
   /*
    * public void EditGroup(uint $id, string $name, string $description)
    * 
    * w grupie o ID=$id zmienia nazwę na $name i opis na $description
    */
   
   public function EditGroup($id, $name, $description)
   {
      $id = intval($id);
      $name = mysql_real_escape_string($name);
      $description = mysql_real_escape_string($description);
      
      $this->db->query("UPDATE `__download_groups` SET `name` = '%1', `description` = '%2' WHERE `id` = '%3'", $name, $description, $id);
   }
   /*
    * public DBresult GroupData(uint $id)
    * 
    * pobiera dane grupy plików o ID=$id
    */
   
   public function GroupData($id)
   {
      $id = intval($id);
      
      return $this->db->query("SELECT * FROM `__download_groups` WHERE `id` = '%1'", $id);
   }
   
   /*
    * public DBresult GetFiles(uint $group)
    * 
    * pobiera pliki w grupie o ID=$group
    */
   
   public function GetFiles($group)
   {
      $group = intval($group);
      
      return $this->db->query("SELECT * FROM `__download_files` WHERE `parent` = '%1'", $group);
   }
}
?>