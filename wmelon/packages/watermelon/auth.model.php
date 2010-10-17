<?php
 //  
 //  This file is part of Watermelon CMS
 //  
 //  Copyright 2010 Radosław Pietruszewski.
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

/*
 * Auth model
 */

class Auth_Model extends Model
{
   /*
    * public object userData_login(string $login)
    * 
    * Data of user with $login login
    * 
    * Returns FALSE if doesn't exist
    */
   
   public function userData_login($login)
   {
      $login = (string) $login;
      
      $userData = $this->db->query("SELECT * FROM `__users` WHERE `user_login` = '%1'", $login);
      
      if(!$userData->exists)
      {
         return false;
      }
      else
      {
         return $userData->fetchObject();
      }
   }
   
   /*
    * public object userData_id(int $uid)
    * 
    * Data of user with $uid ID
    * 
    * Returns FALSE if doesn't exist
    */
   
   public function userData_id($uid)
   {
      $uid = (int) $uid;
      
      $userData = $this->db->query("SELECT * FROM `__users` WHERE `user_id` = '%1'", $uid);
      
      if(!$userData->exists)
      {
         return false;
      }
      else
      {
         return $userData->fetchObject();
      }
   }
   
   /*
    * public void updateLastSeen(int $uid)
    * 
    * Updates `lastseen` field for user with $uid ID
    */
   
   public function updateLastSeen($uid)
   {
      $uid = (int) $uid;
      
      $this->db->query("UPDATE `__users` SET `user_lastseen` = '%2' WHERE `user_id` = '%1'", $uid, time());
   }
}