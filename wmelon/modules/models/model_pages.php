<?php
 //  
 //  Watermelon CMS
 //  
 //  Copyright 2008-2009 Radosław Pietruszewski.
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

class Model_Pages extends Model
{
   /*
    * public DBresult GetPages()
    * 
    * pobiera strony
    */
   
   public function GetPages()
   {
      return $this->db->query("SELECT * FROM `__pages` ORDER BY `id` DESC");
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

   /*
    * public DBresult GetData(int $pageID)
    *
    * zwraca dane page'a o ID $pageID
    */

   public function GetDataByID($pageID)
   {
      $pageID = intval($pageID);

      return $this->db->query("SELECT * FROM `__pages` WHERE `id` = '%1'", $pageID);
   }
   
   /*
    * public void Post(string $title, string $name, string $text)
    * 
    * tworzy stronę o tytule $title, z treścią $text i nazwą $name
    */
   
   public function Post($title, $name, $text)
   {
      $title = mysql_real_escape_string($title);
      $name  = mysql_real_escape_string($name);
      $text  = mysql_real_escape_string($text);
      
      $this->db->query("INSERT INTO `__pages` (`name`, `content`, `title`) VALUES ('%1', '%2', '%3')", $name, $text, $title);
   }
   
   /*
    * public void Edit(uint $id, string $title, string $name, string $text)
    * 
    * ustala stronie o ID = $id tytuł $title, nazwę $name oraz treść $text
    */
   
   public function Edit($id, $title, $name, $text)
   {
      $title = mysql_real_escape_string($title);
      $text  = mysql_real_escape_string($text);
      $name  = mysql_real_escape_string($name);
      $id    = intval($id);
      
      $this->db->query("UPDATE `__pages` SET `title` = '%1', `content` = '%2', `name` = '%3' WHERE `id` = '%4'", $title, $text, $name, $id);
   }
   
   /*
    * public void Delete(uint $id)
    * 
    * Usuwa stronę o ID = $id
    */
   
   public function Delete($id)
   {
      $id = intval($id);
      $this->db->query("DELETE FROM `__pages` WHERE `id` = '%1'", $id);
   }
}
?>