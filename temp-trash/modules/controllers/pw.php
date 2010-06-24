<?php
 //  
 //  Watermelon CMS
 //  
 //  Copyright 2008-2009 Radosław Pietruszewski.
 //  
 //  This program is free software: you can redistribute it and/or modify
 //  it under the terms of the GNU General Public License as published by
 //  the Free Software Foundation, either version 3 of the License, or
 //  (at your option) any later version.
 //  
 //  This program is distributed in the hope that it will be useful,
 //  but WITHOUT ANY WARRANTY; without even the implied warranty of
 //  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 //  GNU General Public License for more details.
 //  
 //  You should have received a copy of the GNU General Public License
 //  along with this program.  If not, see <http://www.gnu.org/licenses/>.
 //  

class PW extends Controller
{
   public function __construct()
   {
      parent::__construct();
      
      if(!$this->user->IsLoggedIn())
      {
         siteredirect('');
      }
   }
   
   /*
    * Lista prywatnych wiadomości
    */

   public function Index()
   {
      Watermelon::addmsgs('pw_sent', 'pw_deleted');
      
      SetH1('Prywatne wiadomości');
      
      // pobieramy listę PW (jako argument nad UID)
      
      $pwlist = model('PW')->GetPWList($_SESSION['WM_UID']);
      
      // sprawdzamy, czy mamy jakieś pw
      
      if(!$pwlist->exists())
      {
         echo $this->load->view('pw_nopws');
      }
      else
      {
         // wyświetlamy listę
         
         echo $this->load->view('pw_list', array('pwlist' => $pwlist));
      }
   }
   
   /*
    * Wyświetlenie prywatnej wiadomości
    * /pw/view/PW_ID
    */
   
   public function View()
   {
      // id prywatnej wiadomości do wyświetlenia
      
      $pw_id = $this->url->segment(1);
      
      // ładujemy dane na temat prywatnej wiadomości
      
      $pw_data = model('PW')->GetPWData($pw_id);
      
      // sprawdzamy, czy w ogóle taka istnieje
      
      if(!$pw_data->exists())
      {
         echo $this->load->view('pw_nosuch');
         return;
      }
      
      // sprawdzamy, czy możemy przeczytać tą wiadmość (czy jesteśmy jej odbiorcą)
      
      $pw_data = $pw_data->to_obj();
      
      if($pw_data->to != $_SESSION['WM_UID'])
      {
         echo $this->load->view('pw_cannotview');
         return;
      }
      
      // jeśli wszystko jest ok...
      
      SetH1('PW: ' . $pw_data->subject);
      
      echo $this->load->view('pw_pw', objectToArray($pw_data));
      
      // ustawiamy flagę "przeczytano"
      
      model('PW')->SetReaded($pw_id);
   }
   
   /*
    * Nowa prywatna wiadomość
    */
   
   public function _New()
   {
      SetH1('Nowa prywatna wiadomość');
      
      $adressee = $this->url->segment(1);
      
      echo $this->load->view('pw_new', array('adressee' => $adressee));
   }
   
   /*
    * odpowiedź na prywatną wiadomość
    */
   
   public function Response()
   {
      $pw_id = $this->url->segment(1);
      
      // ładujemy dane na temat prywatnej wiadomości
      
      $pw_data = model('PW')->GetPWData($pw_id);
      
      // sprawdzamy, czy w ogóle taka istnieje
      
      if(!$pw_data->exists())
      {
         echo $this->load->view('pw_nosuch');
         return;
      }
      
      // sprawdzamy, czy możemy przeczytać tą wiadmość (czy jesteśmy jej odbiorcą)
      
      $pw_data = $pw_data->to_obj();
      
      if($pw_data->to != $_SESSION['WM_UID'])
      {
         echo $this->load->view('pw_cannotview');
         return;
      }
      
      // jeśli wszystko jest ok...
      
      SetH1('Nowa prywatna wiadomość');
      
      echo $this->load->view('pw_response', objectToArray($pw_data));
   }
   
   /*
    * Wysyłanie prywatnej wiadomości
    */
   
   public function Send()
   {
      // sprawdzamy, czy zostały uzupełnione wszystkie pola.
      
      if(empty($_POST['addressee']) ||empty($_POST['subject']) || empty($_POST['text']))
      {
         echo $this->load->view('allfieldsneeded');
         return;
      }
      
      // sprawdzamy czy adresat istnieje
      
      $this->PW = model('PW');
      
      $addresseeUID = $this->PW->GetAddresseeUID($_POST['addressee']);
      
      if(!$addresseeUID->exists())
      {
         echo $this->load->view('nosuchuser');
         return;
      }
      
      // skoro wszystko ok, to wysyłamy
      
      $this->PW->SendPW($_SESSION['WM_UID'], $addresseeUID->to_obj()->id,
                        $_POST['subject'], $_POST['text'], time());
      
      siteredirect('msg:pw_sent/pw');
   }
   
   /*
    * (samo potwierdzenie) Usuwanie prywatnej wiadomości
    */
   
   public function Delete()
   {
      $pw_id = $this->url->segment(1);
      
      // sprawdzamy, czy możemy przeczytać tą wiadmość (czy jesteśmy jej odbiorcą)
      // przy okazji sprawdza, czy PW istnieje (jeśli nie istnieje to warunek i tak
      // nie zostanie spełniony)
      
      if(model('PW')->GetPWAddressee($pw_id) != $_SESSION['WM_UID'])
      {
         echo $this->load->view('pw_cannotdelete');
         return;
      }
      
      // tworzymy klucz tymczasowy
      
      list($tempKey, $tempKeyValue) = model('TempKeys')->MakeKey($pw_id);
      
      // formularz "czy na pewno usunąć"
      
      echo $this->load->view('pw_deletequestion', array('tempkey' => $tempKey, 'tempkeyvalue' => $tempKeyValue, 'pwid' => $pw_id));
   }
   
   /*
    * usuwanie prywatnej wiadomości
    */
   
   public function Delete_ok()
   {
      $tempKey = $this->url->segment(1);
      $tempKeyValue = $this->url->segment(2);
      $pw_id = $this->url->segment(3);
      
      // sprawdzamy, czy z kluczem tymczasowym wszystko w porządku
      
      if(!model('TempKeys')->CheckKey($tempKey, $tempKeyValue, $pw_id))
      {
         echo $this->load->view('pw_cannotdelete');
         return;
      }
      
      // skoro tak, to usuwamy
      
      model('PW')->Delete($pw_id);
      
      siteredirect('msg:pw_deleted/pw');
   }
   
}
?>
