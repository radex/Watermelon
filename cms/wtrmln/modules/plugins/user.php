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

class User extends Plugin
{
   /*
    * private Model_User $User
    *
    * Instancja klasy Model_User
    */
   private $User;

   /*
    * public void User()
    *
    * Konstruktor. Uzupełnia pole $User.
    */

   public function User()
   {
      parent::Plugin();

      $this->User = $this->load->model('user');
   }

   /*
    * public bool Login(string $user, string $password, bool $autologin)
    *
    * Loguje użytkownika. Zwraca true, jeśli logowanie wypadło pomyślnie, w
    * przeciwnym wypadku zwraca false.
    *
    * string $user      - Nazwa użytkownika
    * string $password  - Hasło użytkownika (w czystej postaci)
    * bool   $autologin - Zalogować usera automatycznie przy każdej wizycie?
    *                       TRUE - Tak, loguj mnie automatycznie
    *                       FALSE - Nie loguj mnie automatycznie!
    */

   public function Login($user, $password, $autologin)
   {
      // Walidacja wprowadzonych danych
      
      var_dump($autologin);

      if(empty($user))
      {
         $errors[] = 'Pole <em>login</em> musi być wypełnione.';
      }

      if(empty($password))
      {
         $errors[] = 'Pole <em>hasło</em> musi być wypełnione.';
      }

      if(isset($errors))
      {
         setH1('Błąd logowania');

         echo $this->load->view('login_loginerrors', array('errors' => $errors));
         return false;
      }

      // sprawdzamy, czy user istnieje

      $userdata = $this->User->LoginUserData($user);

      if($userdata->num_rows() == 0)
      {
         setH1('Błąd logowania');

         $errors[] = 'Użytkownik <em>' . $user . '</em> nie istnieje.';

         echo $this->load->view('login_loginerrors', array('errors' => $errors));
         return false;
      }
      
      // sprawdzamy poprawność hasła

      $userdata = $userdata->to_obj();

      if($userdata->password != strHash($password . $userdata->salt, intval($userdata->hashalgo)))
      {
         setH1('Błąd logowania');

         $errors[] = 'Niepoprawne hasło.';

         echo $this->load->view('login_loginerrors', array('errors' => $errors));
         return false;
      }

      echo 'bez błędów :P';

      //TODO
   }

   public function Register($user, $password, $password2, $email, $email2, $data)
   {
      //TODO
   }

   public function GetUserData($dataname)
   {
      //TODO
   }

   public function Logout()
   {
      //TODO
   }

   // TODO
}

?>