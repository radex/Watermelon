<?php
 //  
 //  This file is part of Watermelon CMS
 //  
 //  Copyright 2009 Radosław Pietruszewski.
 //  
 //  Watermelon CMS is free software: you can redistribute it and/or modify
 //  it under the terms of the GNU General Public License as published by
 //  the Free Software Foundation, either version 3 of the License, or
 //  (at your option) any later version.
 //  
 //  Watermelon CMS is distributed in the hope that it will be useful,
 //  but WITHOUT ANY WARRANTY; without even the implied warranty of
 //  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 //  GNU General Public License for more details.
 //  
 //  You should have received a copy of the GNU General Public License
 //  along with Watermelon CMS. If not, see <http://www.gnu.org/licenses/>.
 //  

class Model_News extends Model
{
   /*
    * public DBresult GetNews()
    * 
    * pobiera newsy
    */
   
   public function GetNews()
   {
      return $this->db->query("SELECT * FROM `__news` ORDER BY `id` DESC");
   }
   
   /*
    * public void Post(string $title, string $text, uint $uid)
    * 
    * tworzy newsa o tytule $title, z treścią $text jako użytkownik o ID = $uid
    */
   
   public function Post($title, $text, $uid)
   {
      $title = mysql_real_escape_string($title);
      $text  = mysql_real_escape_string($text);
      $uid   = intval($uid);
      
      $this->db->query("INSERT INTO `__news` (`title`, `text`, `author`, `date`) VALUES ('%1', '%2', '%3', '%4')", $title, $text, $uid, time());
   }
   
   /*
    * public DBresult GetData(uint $id)
    * 
    * pobiera dane newsa o ID = $id
    */
   
   public function GetData($id)
   {
      $id = intval($id);
      
      return $this->db->query("SELECT * FROM `__news` WHERE `id` = '%1'", $id);
   }
   
   /*
    * public void Edit(uint $id, string $title, string $text)
    * 
    * ustala newsowi o ID = $id tytuł $title oraz treść $text
    */
   
   public function Edit($id, $title, $text)
   {
      $title = mysql_real_escape_string($title);
      $text  = mysql_real_escape_string($text);
      $id    = intval($id);
      
      $this->db->query("UPDATE `__news` SET `title` = '%1', `text` = '%2' WHERE `id` = '%3'", $title, $text, $id);
   }
   
   /*
    * public void Delete(uint $id)
    * 
    * Usuwa newsa o ID = $id
    */
   
   public function Delete($id)
   {
      $id = intval($id);
      $this->db->query("DELETE FROM `__news` WHERE `id` = '%1'", $id);
   }
}

?>