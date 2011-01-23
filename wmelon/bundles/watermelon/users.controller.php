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
 * Users controller
 * 
 * Logging in and out
 */

class Users_Controller extends Controller
{
   function index_action()
   {
      SiteRedirect('users/login');
   }
   
   /*
    * logging in
    */
   
   function login_action($backPage = '')
   {
      $this->pageTitle = 'Logowanie';

      $form = new Form('wmelon.auth.login', 'users/loginSubmit', 'users/login');
      $form->submitLabel = 'Zaloguj';
      
      $form->addInput('text', 'login', 'Login');
      $form->addInput('password', 'pass', 'Hasło');
      $form->addInput('hidden', 'backPage', $backPage);
      
      echo $form->generate();
   }
   
   /*
    * logging in - submit
    */
   
   function loginSubmit_action()
   {
      $form = Form::validate('wmelon.auth.login', 'users/login');
      $data = $form->get();
      
      // validating
      
      try
      {
         Users::login($data->login, $data->pass);
      }
      catch(WMException $e)
      {
         if($e->getCode() == 'auth:userNotExists')
         {
            $form->addError('Podany użytkownik nie istnieje');
            $form->fallback();
         }
         elseif($e->getCode() == 'auth:wrongPass')
         {
            $form->addError('Podano złe hasło');
            $form->fallback();
         }
      }
      
      // redirecting
      
      SiteRedirect(base64_decode($data->backPage));
   }
   
   /*
    * logging out
    */
   
   function logout_action()
   {
      Users::logout();
      
      if(!empty($_SERVER['HTTP_REFERER']))
      {
         Redirect($_SERVER['HTTP_REFERER']);
      }
      else
      {
         SiteRedirect();
      }
   }
}