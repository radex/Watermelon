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

class User extends Plugin
{
   /*
    * private Model_User $User
    * 
    * Instancja klasy Model_User
    */
   private $User;
   
   /*
    * private static bool $LoggedIn
    * 
    * stan użytkownika (zalogowany/niezalogowany)
    */
   
   private static $LoggedIn = null;
   
   /*
    * private static object[] $users
    * 
    * tablica z danymi userów w postaci obiektu.
    * 
    * $users = array(UID => object(...), UID => object())
    */
   
   private static $users;

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
    *                       FALSE - Nie loguj mnie automatycznie
    */

   public function Login($user, $password, $autologin)
   {
      // Walidacja wprowadzonych danych

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
      
      // generujemy nowy salt i hash i
      // zmieniamy hashalgo (jeśli nieaktualne)
      
      $salt = strHash(uniqid(mt_rand(), true));
      
      $salt = substr($salt, 0, 16);
      
      $hash = strHash($password . $salt);
      
      $this->User->updatePassword($user, $hash, $salt, Config::$defaultHashAlgo);
      
      // logujemy :]
      
      $_SESSION['WTRMLN_USER'] = $user;
      $_SESSION['WTRMLN_UID']  = $userdata->id;
      $_SESSION['WTRMLN_PASS'] = $hash;
      $_SESSION['WTRMLN_LASTSEEN'] = time();

      // jeszcze informacyjka
      
      //setH1('Logowanie udane');
      
      //echo $this->load->view('login_success');
      
      redirect(site_url('msg:login_success'));
      
      return true;
   }

   public function Register($user, $password, $password2, $email, $email2, $data)
   {
      //TODO
   }
   
   /*
    * public void Logout()
    * 
    * wylogowuje użytkownika (niszczy sesję, nie pokazuje żadnych treści)
    */
   
   public function Logout()
   {
      session_destroy();
      
      self::$LoggedIn = false;
   }
   
   /*
    * public bool IsLoggedIn()
    * 
    * Sprawdza, czy użytkownik jest zalogowany.
    * 
    * zwraca true, jeśli jeśli użytkownik jest zalogowany,
    * w przeciwnym wypadku zwraca false.
    */
   
   public function IsLoggedIn()
   {
      // sprawdzamy, czy już wcześniej funkcja była odpalana
      
      if(self::$LoggedIn !== null)
      {
         return self::$LoggedIn;
      }
      
      // sprawdzamy, czy sesja istnieje
      
      if(!isset($_SESSION['WTRMLN_USER']))
      {
         self::$LoggedIn = false;
         return false;
      }
      
      // sprawdzamy, czy user istnieje
      
      $userdata = $this->User->LoginUserData($_SESSION['WTRMLN_USER']);
      
      if(!$userdata->exists())
      {
         self::$LoggedIn = false;
         return false;
      }
      
      // sprawdzamy, czy UID pasuje do nicka
      
      $userdata = $userdata->to_obj();
      
      if($userdata->id != $_SESSION['WTRMLN_UID'])
      {
         self::$LoggedIn = false;
         return false;
      }
      
      // sprawdzamy poprawność hasła

      if($_SESSION['WTRMLN_PASS'] != $userdata->password)
      {
         self::$LoggedIn = false;
         return false;
      }
      
      // sprawdzamy kiedy ostatnio był użytkownik
      // jeśli przekroczony limit długości sesji (1800 sekund)
      // automatycznie wylogowuje
      
      if($_SESSION['WTRMLN_LASTSEEN'] < time() - 1800)
      {
         $this->Logout();
         return false;
      }
      
      // aktualizujemy dane
      
      $_SESSION['WTRMLN_LASTSEEN'] = time();
      
      self::$LoggedIn = true;
      
      $this->User->UpdateLastSeen($_SESSION['WTRMLN_UID']);
      
      return true;
   }
   
   /*
    * public bool IsAdmin()
    * 
    * sprawdza, czy zalogowany użytkownik jest adminem.
    * jednocześnie sprawdza, czy w ogóle jest zalogowany.
    * 
    * zwraca true, jeśil zalogowany użytkownik jest adminem,
    * a gdy użytkownik nie jest adminem, lub w ogóle nie jest
    * zalogowany zwraca false.
    */
   
   public function IsAdmin()
   {
      // sprawdzamy, czy w ogóle jest zalogowany
      
      if(!$this->IsLoggedIn())
      {
         return false;
      }
      
      // sprawdzamy, czy jest w tablicy superusers
      
      $superusers = Config::getSuperusers();
      
      if(array_search($_SESSION['WTRMLN_USER'], $superusers) === false)
      {
         return false;
      }
      
      // sprawdzamy, czy ma odpowiednie uprawnienia w bazie danych
      
      $priviliges = $this->getData($_SESSION['WTRMLN_UID']);
      
      if($priviliges->a_paaccess !== '1')
      {
         return false;
      }
      
      return true;
   }
   
   /*
    * public static string getNick(uint $uid)
    * 
    * zwraca nick danego użytkownika na podstawie UID
    * 
    * uint $uid - ID użytkownika, którego nick ma zostać zwrócony
    */
   
   public static function getNick($uid)
   {
      $uid = intval($uid);
      
      if(isset(self::$users[$uid]))
      {
         return self::$users[$uid]->nick;
      }
      
      $data = Loader::model('user')->UserData($uid)->to_obj();
      
      self::$users[$uid] = $data;
      
      return $data->nick;
   }
   
   /*
    * public static string getData(uint $uid)
    * 
    * zwraca dane danego użytkownika na podstawie UID
    * 
    * uint $uid - ID użytkownika, którego dane mają zostać zwrócone
    */
   
   public static function getData($uid)
   {
      $uid = intval($uid);
      
      if(isset(self::$users[$uid]))
      {
         return self::$users[$uid];
      }
      
      $data = Loader::model('user')->UserData($uid)->to_obj();
      
      self::$users[$uid] = $data;
      
      return $data;
   }
}

?>