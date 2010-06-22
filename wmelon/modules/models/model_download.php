<?php
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
   
   /*
    * public DBresult FileData(uint $id)
    * 
    * pobiera dane pliku o ID=$id
    */
   
   public function FileData($id)
   {
      $id = intval($id);
      
      return $this->db->query("SELECT * FROM `__download_files` WHERE `id` = '%1'", $id);
   }
   
   /*
    * public void PostFile(string $file, string $link, string $description, string $size, uint $id)
    * 
    * tworzy w grupie o ID=$id plik $file opisany $description o wielkości $size
    * znajdujący się pod adresem $link
    */
   
   public function PostFile($file, $link, $description, $size, $id)
   {
      $file = mysql_real_escape_string($file);
      $link = mysql_real_escape_string($link);
      $description = mysql_real_escape_string($description);
      $size = mysql_real_escape_string($size);
      $id = intval($id);
      
      $this->db->query("INSERT INTO `__download_files` (`parent`, `file`, `description`, `date`, `downloads`, `size`, `url`) VALUES ('%1', '%2', '%3', '%4', '%5', '%6', '%7')", $id, $file, $description, time(), 0, $size, $link);
      
      $this->db->query("UPDATE `__download_groups` SET `files` = `files` + 1 WHERE `id` = '%1'", $id);
   }
   
   /*
    * public uint FileGID(uint $id)
    * 
    * zwraca ID grupy pliku o ID=$id
    */
   
   public function FileGID($id)
   {
      return $this->db->query("SELECT `parent` FROM `__download_files` WHERE `id` = '%1'", $id)->to_obj()->parent;
   }
   
   /*
    * public uint EditFile(string $file, string $link, string $description, string $size, uint $id)
    * 
    * zmienia w pliku o ID=$id nazwę na $file, link na $link, opis na $description i wielkość na $size.
    * zwraca ID grupy, w której ten plik się znajduje
    */
   
   public function EditFile($file, $link, $description, $size, $id)
   {
      $file = mysql_real_escape_string($file);
      $link = mysql_real_escape_string($link);
      $description = mysql_real_escape_string($description);
      $size = mysql_real_escape_string($size);
      $id = intval($id);
      
      $this->db->query("UPDATE `__download_files` SET `file` = '%1', `description` = '%2', `size` = '%3', `url` = '%4' WHERE `id` = '%5'", $file, $description, $size, $link, $id);
      
      return $this->FileGID($id);
   }
   
   /*
    * public DBresult DeleteFile(uint $id)
    * 
    * usuwa plik o ID=$id
    */
   
   public function DeleteFile($id)
   {
      $id = intval($id);
      
      $gid = $this->FileGID($id);
      
      $this->db->query("DELETE FROM `__download_files` WHERE `id` = '%1'", $id);
      
      $this->db->query("UPDATE `__download_groups` SET `files` = `files` - 1 WHERE `id` = '%1'", $gid);
      
      return $gid;
   }
   
   /*
    * public void IncDownloads(uint $id)
    * 
    * inkrementuje licznik ściągnięć pliku o ID=$id
    */
   
   public function IncDownloads($id)
   {
      $id = intval($id);
      
      $this->db->query("UPDATE `__download_files` SET `downloads` = `downloads` + 1 WHERE `id` = '%1'", $id);
   }
}
?>