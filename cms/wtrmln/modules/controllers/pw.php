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

class PW extends Controller
{
   public function PW()
   {
      parent::Controller();
   
      if(!$this->user->IsLoggedIn())
      {
         header('Location: ' . site_url(''));
      }
   }
   
   /*
    * Lista prywatnych wiadomości
    */

   public function Index()
   {
      SetH1('Prywatne wiadomości');
      
      $this->PW = $this->load->model('PW');
      
      // pobieramy listę PW (jako argument nad UID)
      
      $pwlist = $this->PW->GetPWList($_SESSION['WTRMLN_UID']);
      
      // wyświetlamy listę
      
      echo $this->load->view('profile_pwlist', array('pwlist' => $pwlist));
   }
   
   /*
    * Wyświetlenie prywatnej wiadomości
    * /pw/view/PW_ID
    */
   
   public function View()
   {
      // id prywatnej wiadomości do wyświetlenia
      
      $pw_id = $this->url->segment(1);
      
      $this->PW = $this->load->model('PW');
      
      // ładujemy dane na temat prywatnej wiadomości
      
      $pw_data = $this->PW->GetPWData($pw_id);
      
      // sprawdzamy, czy w ogóle taka istnieje
      
      if($pw_data->num_rows() == 0)
      {
         echo $this->load->view('profile_nosuchpw');
         return;
      }
      
      // sprawdzamy, czy możemy przeczytać tą wiadmość (czy jesteśmy jej odbiorcą)
      
      $pw_data = $pw_data->to_obj();
      
      if($pw_data->to != $_SESSION['WTRMLN_UID'])
      {
         echo $this->load->view('profile_cannotviewpw');
         return;
      }
      
      // jeśli wszystko jest ok...
      
      SetH1('PW: ' . $pw_data->subject);
      
      $pw_data->text = nl2br($pw_data->text);
      
      echo $this->load->view('profile_pw', objectToArray($pw_data));
   }
   
   /*
    * Nowa prywatna wiadomość
    */
   
   public function _New()
   {
      SetH1('Nowa prywatna wiadomość');
      
      echo $this->load->view('profile_newpw');
   }
   
   /*
    * Wysyłanie prywatnej wiadomości
    */
   
   public function Send()
   {
      // sprawdzamy, czy zostały uzupełnione wszystkie pola.
      
      if(empty($_POST['addressee']) ||
         empty($_POST['subject'])   || 
         empty($_POST['text']))
      {
         echo $this->load->view('allfieldsneeded');
         return;
      }
      
      // sprawdzamy czy adresat istnieje
      
      $this->PW = $this->load->model('PW');
      
      $addresseeUID = $this->PW->GetAddresseeUID($_POST['addressee']);
      
      if($addresseeUID->num_rows() == 0)
      {
         echo $this->load->view('nosuchuser');
         return;
      }
      
      // skoro wszystko ok, to wysyłamy
      
      $this->PW->SendPW($_SESSION['WTRMLN_UID'], $addresseeUID->to_obj()->id,
                        $_POST['subject'], $_POST['text'], time());
      
      echo $this->load->view("profile_pwsent");
   }
   
   /*
    * Usuwanie prywatnej wiadomości
    */
   
   public function Delete()
   {
      $pw_id = $this->url->segment(1);
      
      // sprawdzamy, czy możemy przeczytać tą wiadmość (czy jesteśmy jej odbiorcą)
      
      $this->PW = $this->load->model('PW');
      
      if($this->PW->GetPWAddressee($pw_id) != $_SESSION['WTRMLN_UID'])
      {
         echo $this->load->view('profile_cannotdeletepw');
         return;
      }
      
      // TODO: Wymagamy potwierdzenia decyzji
      
      // skoro jesteśmy to usuwamy
      
      $this->PW->Delete($pw_id);
      
      echo $this->load->view("profile_pwdeleted");
   }
}
?>
