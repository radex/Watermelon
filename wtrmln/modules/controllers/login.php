<?php
/********************************************************************

  Watermelon CMS

Copyright 2008-2009 Radosław Pietruszewski

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
version 2 as published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.

********************************************************************/

class Login extends Controller
{
   /*
    * stronka z formularzem logowania
    */

   function Index()
   {
      if(!$this->user->isLoggedIn())
      {
         setH1('Logowanie');
         
         $this->addMeta('<style type="text/css">.loginform{text-align:left}.loginform label{float:left;width:130px;display:block}</style>');
         
         echo $this->load->view('login_form');
      }
      else
      {
         echo $this->load->view('login_youareloggedin');
      }
   }

   /*
    * samo logowanie
    */

   function Submit()
   {
      if($this->user->isLoggedIn())
      {
         echo $this->load->view('login_youareloggedin');
         return;
      }
      
      $login     = $_POST['login'];
      $pass      = $_POST['password'];
      $autologin = isset($_POST['autologin']);
      
      $this->user->Login($login, $pass, $autologin);
   }

   /*
    * formularz przysłania nowego hasła
    */

   function SendNewPassword()
   {
      setH1('Formularz wysyłania nowego hasła');

      $this->addMeta('<style type="text/css">.sendpassform label{float:left;width:160px;display:block}</style>');

      echo $this->load->view('login_sendnewpassword');
   }
   
   /*
    * wylogowanie
    */
   
   function Logout()
   {
      $this->user->logout();
      
      siteredirect('msg:login_loggedout');
   }

   /*
    * przysyłanie nowego hasła (walidacja itd.)
    */

   function SendNewPasswordSubmit()
   {
      //TODO
   }
}
?>
