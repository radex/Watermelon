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
 * Auth controller
 * 
 * Logging in and out
 */

class Auth_Controller extends Controller
{
   function index_action()
   {
      SiteRedirect('auth/login');
   }
   
   /*
    * logging in
    */
   
   function login_action()
   {
      $this->pageTitle = 'Logowanie';
      
      View('login')->display();
   }
   
   /*
    * logging in - submit
    */
   
   function loginSubmit_action()
   {
      $login = $_POST['login'];
      $pass  = $_POST['pass'];
      
      // checking whether login and password are given
      
      if(empty($login) || empty($pass))
      {
         Watermelon::addMessage('error', 'wszystkie pola wymagane');
         SiteRedirect('auth/login');
      }
      
      // logging in
      
      try
      {
         Auth::login($login, $pass);
      }
      catch(WMException $e)
      {
         Watermelon::addMessage('error', $e->getMessage());
         SiteRedirect('auth/login');
      }
      
      // if logged in properly
      
      SiteRedirect();
   }
   
   /*
    * logging out
    */
   
   function logout_action()
   {
      Auth::logout();
      
      SiteRedirect();
   }
}