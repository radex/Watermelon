<?php
 //  
 //  This file is part of Watermelon
 //  
 //  Copyright 2011 Radosław Pietruszewski.
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
 * Users: authorization and admin's data
 */

class Users extends Extension
{
   private static $isLogged = null; // (bool) cached after calling ::isLogged()
   private static $userData = null; // (object) cached after successful login
   
   /**************************************************************************/
   
   /*
    * public static bool isLogged()
    * 
    * Returns whether user is logged in
    * 
    * (makes auto-logging using data from session)
    */
   
   public static function isLogged()
   {
      // return cached if present
      
      if(self::$isLogged !== null)
      {
         return self::$isLogged;
      }
      
      // check for session
      
      $login = $_SESSION['wmelon.users.login'];
      $pass  = $_SESSION['wmelon.users.pass'];
      
      if(empty($login) || empty($pass))
      {
         self::$isLogged = false;
         return false;
      }
      
      // test if data's correct
      
      $userData = Config::get('wmelon.admin');
      
      if($login == $userData->login && $pass == $userData->pass)
      {
         self::$isLogged = true;
         self::$userData = $userData;
         
         return true;
      }
      else
      {
         self::$isLogged = false;
         return false;
      }
   }
   
   /*
    * public static object userData([int $uid])
    * 
    * If called with no arguments, returns data of currently logged in user (or null if no user logged in)
    * 
    * If called with $uid, returns user data od $uid user
    * (currently it's hardcoded to always return admin data, since there are no other users)
    * 
    * Object it returns currently consists of:
    *    ->login - name used for logging in
    *    ->salt  - salt used for hashing password
    *    ->pass  - password hashed using sha1(actual_pass . salt)
    *    ->nick  - name displayed (in comments etc.)
    */
   
   public static function userData($uid = null)
   {
      if(is_int($uid))
      {
         return Config::get('wmelon.admin');
      }
      else
      {
         self::isLogged(); // making sure that auto-logged before returning user data
      
         return self::$userData;
      }
   }
   
   /**************************************************************************/
   
   /*
    * public static bool login(string $login, string $pass)
    * 
    * Tries to log in using given data (and if successful, saves login data in session)
    * 
    * When successful, returns true
    * When user with $login does not exist, raises WMException with code 'users:doesNotExist'
    * When given password is not correct, raises WMException[users:wrongPassword]
    */
   
   public static function login($login, $pass)
   {
      $login = strtolower(trim($login));
      $pass  = trim($pass);
      
      // fetching user data
      
      $userData = Config::get('wmelon.admin');
      
      // checking if given data is correct
      
      if($login != strtolower($userData->login))
      {
         throw new WMException('User does not exist', 'users:doesNotExist');
      }
      
      if(sha1($pass . $userData->salt) != $userData->pass)
      {
         throw new WMException('Wrong password', 'users:wrongPassword');
      }
      
      // everything seems fine
      
      self::$isLogged = true;
      self::$userData = $userData;
      
      $_SESSION['wmelon.users.login'] = $userData->login;
      $_SESSION['wmelon.users.pass']  = $userData->pass;
      
      return true;
   }
   
   /*
    * public static void logout()
    * 
    * Logs out and destroys user session
    */
   
   public static function logout()
   {
      self::$isLogged = false;
      self::$userData = null;
      
      unset($_SESSION['wmelon.users.login'], $_SESSION['wmelon.users.pass']);
   }
}