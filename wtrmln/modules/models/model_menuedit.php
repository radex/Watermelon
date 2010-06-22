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

class Model_MenuEdit extends Model
{
   /*
    * public DBresult GetMenus()
    * 
    * pobiera listę menu
    */
   
   public function GetMenus()
   {
      return $this->db->query("SELECT * FROM `__menu` ORDER BY `position`, `id`");
   }
   
   /*
    * public DBresult GetMenuData(uint $id)
    * 
    * pobiera dane menu o id = $id
    */
   
   public function GetMenuData($id)
   {
      $id = intval($id);
      
      return $this->db->query("SELECT * FROM `__menu` WHERE `id` = '%1'", $id);
   }
   
   /*
    * public void addMenu(string $name, string $condition, string $text)
    * 
    * dodaje na koniec (ostatnia pozycja) menu o nazwie $name, z treścią
    * $text pod warunkiem $condition
    */
   
   public function addMenu($name, $condition, $text)
   {
      $name = mysql_real_escape_string($name);
      $condition = mysql_real_escape_string($condition);
      $text = mysql_real_escape_string($text);
      
      $position = Config::getConf('max_menu') + 5;
      Config::setConf('max_menu', $position);
      
      $this->db->query("INSERT INTO `__menu` (`position`, `capt`, `content`, `condition`) VALUES ('%1', '%2', '%3', '%4')", $position, $name, $text, $condition);
   }
   
   /*
    * public void Edit(string $name, string $condition, string $text, uint $menuID)
    * 
    * Zmienia w menu o ID = $menuID nazwę na $name,
    * treść na $text i warunek $condition
    */
   
   public function Edit($name, $condition, $text, $menuID)
   {
      $name = mysql_real_escape_string($name);
      $condition = mysql_real_escape_string($condition);
      $text = mysql_real_escape_string($text);
      $menuID = intval($menuID);
      
      $this->db->query("UPDATE `__menu` SET `capt` = '%1', `content` = '%3', `condition` = '%2' WHERE `id` = '%4'", $name, $condition, $text, $menuID);
   }
   
   /*
    * public void DeleteMenu(uint $id)
    * 
    * usuwa menu o id = $id 
    */
   
   public function DeleteMenu($id)
   {
      $id = intval($id);
      
      $this->db->query("DELETE FROM `__menu` WHERE `id` = '%1'", $id);
      
      // uaktualniamy najwyższą pozycję w bazie danych
      
      $lastPos = $this->db->query("SELECT `position` FROM `__menu` ORDER BY `position` DESC LIMIT 1", $id);
      $lastPos = $lastPos->to_obj()->position;
      
      Config::setConf('max_menu', $lastPos);
   }
   
   /*
    * public void SetMenuPosition(uint $id, uint $position)
    * 
    * zmienia pozycję menu o id = $id na $position
    */
   
   public function SetMenuPosition($id, $pos)
   {
      $id  = intval($id);
      $pos = intval($pos);
      
      $this->db->query("UPDATE `__menu` SET `position` = '%2' WHERE `id` = '%1'", $id, $pos);
      
      // uaktualniamy najwyższą pozycję w bazie danych
      
      $lastPos = $this->db->query("SELECT `position` FROM `__menu` ORDER BY `position` DESC LIMIT 1", $id);
      $lastPos = $lastPos->to_obj()->position;
      
      Config::setConf('max_menu', $lastPos);
   }
   
   /*
    * public DBresult GetTopMenus()
    * 
    * pobiera górne menu
    */
   
   public function GetTopMenus()
   {
      $menus = Config::getConf('top_menus');
      
      return unserialize($menus);
   }
   
   /*
    * public void UpdateTopMenus(string[] $menus)
    * 
    * uaktualnia listę (tablicę) górnego menu.
    * 
    * string[] $menus - lista górnego menu do uaktualnienia
    */
   
   public function UpdateTopMenus(array $menus)
   {
      Config::setConf('top_menus', mysql_real_escape_string(serialize($menus)));
   }
   
   /*
    * public void addTopMenu(string $name, string $link, string $condition)
    * 
    * dodaje na koniec (ostatnia pozycja) górne menu o nazwie $name
    * prowadzące do $link, które będzie oznaczone jako aktywne, gdy
    * $condition jest prawdą
    */
   
   public function addTopMenu($name, $link, $content)
   {
      $menus = $this->GetTopMenus();
      $menus[] = array($name, $link, $content);
      $this->UpdateTopMenus($menus);
   }
   
   /*
    * public void TopEdit(string $name, string $link, string $condition, uint $id)
    * 
    * edytuje w górnym menu o ID=$id nazwę na $name
    * prowadzące do $link, które będzie oznaczone jako aktywne, gdy
    * $condition jest prawdą
    */
   
   public function TopEdit($name, $link, $content, $id)
   {
      $id = intval($id);
      
      $menus = $this->GetTopMenus();
      
      if(!isset($menus[$id])) return;
      
      $menus[$id] = array($name, $link, $content);
      $this->UpdateTopMenus($menus);
   }
   
   /*
    * public void DeleteTopMenu(uint $id)
    * 
    * usuwa górne menu o ID=$id
    */
   
   public function DeleteTopMenu($id)
   {
      $id = intval($id);
      
      $menus = $this->GetTopMenus();
      
      unset($menus[$id]);
      
      foreach($menus as $menu)
      {
         $menus2[] = $menu;
      }
      
      $this->UpdateTopMenus($menus2);
   }
   
   /*
    * public string[] GetPAMenus()
    * 
    * Pobiera listę (w postaci tablicy) menu w PA.
    */
   
   public function GetPAMenus()
   {
      $menus = Config::getConf('PA_menu');
      
      return unserialize($menus);
   }
   
   /*
    * public void UpdatePAMenus(string[] $menus)
    * 
    * uaktualnia listę (tablicę) menu w PA.
    * 
    * string[] $menus - lista menu panelu admina do uaktualnienia
    */
   
   public function UpdatePAMenus(array $menus)
   {
      Config::setConf('PA_menu', serialize($menus));
   }
}
?>