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
 * class Auth
 * 
 * Authorization - login, logout, etc.
 */

class Auth_Extension extends Extension
{
   /*
    * public static bool $isLogged = false
    * 
    * Whether user session exists
    */
   
   public static $isLogged = false;
   
   /*
    * public static void onAutoload()
    * 
    * Sets $isLogged to proper value and if user is logged in, updates 'lastseen' field in database
    */
   
   public static function onAutoload()
   {
      // checking whether user is logged in
      
      if(isset($_SESSION['Auth_login']) && isset($_SESSION['Auth_pass']))
      {
         if(self::isLogged())
         {
            self::$isLogged = true;
         }
         else
         {
            self::$isLogged = false;
         }
      }
      
      // if logged in, updating 'lastseen' field
      
      if(self::$isLogged)
      {
         DB::query("UPDATE `__users` SET `user_lastseen` = NOW()");
      }
   }
   
   private static function isLogged()
   {
      // getting user data, checking existence
      
      $login = $_SESSION['Auth_login'];
      $pass  = $_SESSION['Auth_pass'];
      
      $userdata = DB::query("SELECT * FROM `__users` WHERE `user_login` = '%1'", $login);
      
      if(!$userdata->exists)
      {
         return false;
      }
      
      // checking password
      
      $userdata = $userdata->fetchObject();
      
      $pass = sha1($pass . $userdata->user_salt);
      
      if($pass == $userdata->user_password)
      {
         return true;
      }
   }
   
   /*
    * public static void login(string $login, string $pass)
    * 
    * Logs in $login user
    * 
    * Throws [auth:userNotExist] if passed login doesn't exist in database and [auth:wrongPass] if passed password is not correct
    * 
    * Doesn't return anything if login is successful
    */
   
   public static function login($login, $pass)
   {
      $login = trim(strtolower($login));
      $pass  = trim($pass);
      
      // getting user data, checking existence
      
      $userdata = DB::query("SELECT * FROM `__users` WHERE `user_login` = '%1'", $login);
      
      if(!$userdata->exists)
      {
         throw new WMException('userNotExists', 'auth:userNotExists');
      }
      
      $userdata = $userdata->fetchObject();
      
      // checking password
      
      $passHash = sha1($pass . $userdata->user_salt);
      
      if($passHash != $userdata->user_password)
      {
         throw new WMException('wrongPass', 'auth:wrongPass');
      }
      
      // creating session and updating 'lastseen' field
      
      DB::query("UPDATE `__users` SET `user_lastseen` = NOW()");
      
      $_SESSION['Auth_login'] = $login;
      $_SESSION['Auth_pass']  = $pass;
      
      self::$isLogged = true;
   }
   
   /*
    * public static void logout()
    * 
    * Logs logged user out
    */
   
   public static function logout()
   {
      unset($_SESSION['Auth_login']);
      unset($_SESSION['Auth_pass']);
      
      self::$isLogged = false;
   }
}

class Auth extends Auth_Extension{}