<?php
/********************************************************************

  Watermelon CMS

Copyright 2008 Radosław Pietruszewski

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
   private $User;
   public function Login()
   {
      $this->User = new User();
      parent::Controller();
   }

   /*
    * stronka z formularzem logowania
    */

   function Index()
   {
      setH1('Logowanie');

      $this->addMeta('<style type="text/css">.loginform label{float:left;width:140px;display:block}</style>');

      echo $this->load->view('login_form');
   }

   /*
    * samo logowanie
    */

   function Submit()
   {
      $login     = $_POST['login'];
      $pass      = $_POST['password'];
      $autologin = isset($_POST['autologin']);
      
      $this->User->Login($login, $pass, $autologin);

      //TODO
   }

   /*
    * formularz przysłania nowego hasła
    */

   function SendNewPassword()
   {
      setH1('Formularz wysyłania nowego hasła');

      $this->addMeta('<style type="text/css">.sendpassform label{float:left;width:150px;display:block}}</style>');

      echo $this->load->view('login_sendnewpassword');
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
