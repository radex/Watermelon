<?php
 //  
 //  This file is part of Watermelon
 //  
 //  Copyright 2010 Radosław Pietruszewski.
 //  
 //  Watermelon is free software: you can redistribute it and/or modify
 //  it under the terms of the GNU General Public License as published by
 //  the Free Software Foundation, either version 3 of the License, or
 //  (at your option) any later version.
 //  
 //  Watermelon is distributed in the hope that it will be useful,
 //  but WITHOUT ANY WARRANTY; without even the implied warranty of
 //  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 //  GNU General Public License for more details.
 //  
 //  You should have received a copy of the GNU General Public License
 //  along with Watermelon. If not, see <http://www.gnu.org/licenses/>.
 //  

/*
 * Users model
 */

class Users_Model extends Model
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
      return Query::select('users')->where('login', (string) $login)->act()->fetchObject();
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
      return DB::select('users', (int) $uid);
   }
   
   /*
    * public void updateLastSeen(int $uid)
    * 
    * Updates `lastseen` field for user with $uid ID
    */
   
   public function updateLastSeen($uid)
   {
      Query::update('users')->set('lastseen', time())->where('id', (int) $uid)->act();
   }
   
   /*
    * public object privilegesFor(int $uid)
    * 
    * List of privileges, user with $uid ID has
    */
   
   public function privilegesFor($uid)
   {
      $result = Query::select('privileges')->where('user', (int) $uid)->act();
      
      foreach($result as $privilege)
      {
         $privileges[] = $privilege->privilege;
      }
      
      return $privileges;
   }
}