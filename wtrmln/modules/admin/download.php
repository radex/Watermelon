<?php
/********************************************************************

  Watermelon CMS

Copyright 2009 Radosław Pietruszewski

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

class Download extends Controller
{
   /*
    * lista grup
    */
   
   function Index()
   {
      Watermelon::addmsgs('download_groupdeleted', 'download_groupposted', 'download_groupedited');
      
      // pobieramy listę grup
      
      $groups = model('download')->GetGroups();
      
      // sprawdzamy, czy są jakieś grupy
      
      if(!$groups->exists())
      {
         echo $this->load->view('download_nogroups');
         return;
      }
      
      // skoro są, to je wyświetlamy
      
      echo $this->load->view('download_groupstable', array('groups' => $groups));
   }
   
   /*
    * formularz nowej grupy
    */
   
   function newgroup()
   {
      list($tempKey, $tempKeyValue) = model('tempkeys')->MakeKey('newdownloadgroup', time() + 3600);
      
      echo $this->load->view('download_newgroup', array('tkey' => $tempKey, 'tvalue' => $tempKeyValue));
   }
   
   /*
    * stworzenie grupy
    */
   
   function postgroup()
   {
      $tempKey      = $this->url->segment(1);
      $tempKeyValue = $this->url->segment(2);
      
      // sprawdzamy, czy zostały uzupełnione wszystkie pola.
      
      if(empty($_POST['name']))
      {
         echo $this->load->view('allfieldsneeded');
         return;
      }
      
      // sprawdzamy, czy z kluczem tymczasowym wszystko w porządku
      
      if(!model('TempKeys')->CheckKey($tempKey, $tempKeyValue, 'newdownloadgroup'))
      {
         echo $this->load->view('error');
         return;
      }
      
      // skoro tak, to wysyłamy
      
      model('download')->postgroup(htmlspecialchars($_POST['name']), $_POST['description']);
      siteredirect('msg:download_groupposted/download');
   }
   
   /*
    * formularz edycji grupy
    */
   
   function editgroup()
   {
      $id = $this->url->segment(1);
      
      $data = model('download')->GroupData($id);
      
      // sprawdzamy, czy w ogóle taka istnieje
      
      if(!$data->exists())
      {
         echo $this->load->view('download_nosuchgroup');
         return;
      }
      
      // tworzymy klucz tymczasowy
      
      list($tempKey, $tempKeyValue) = model('tempkeys')->MakeKey('editdownloadgroup:' . $id, time() + 3600);
      
      echo $this->load->view('download_editgroup', array('data' => $data->to_obj(), 'tkey' => $tempKey, 'tvalue' => $tempKeyValue));
   }
   
   /*
    * submit: edycja grupy
    */
   
   function editGroupSubmit()
   {
      $tempKey      = $this->url->segment(1);
      $tempKeyValue = $this->url->segment(2);
      $ID           = $this->url->segment(3);
      
      // sprawdzamy, czy z kluczem tymczasowym wszystko w porządku
      
      if(!model('TempKeys')->CheckKey($tempKey, $tempKeyValue, 'editdownloadgroup:' . $ID))
      {
         echo $this->load->view('error');
         return;
      }
      
      // skoro tak, to edytujemy
      
      model('download')->EditGroup($ID, htmlspecialchars($_POST['name']), $_POST['description']);
      
      siteredirect('msg:download_groupedited/download');
   }
   
   /*
    * (samo potwierdznie) usunięcia grupy
    */
   
   function deletegroup()
   {
      $id = $this->url->segment(1);
      
      $data = model('download')->GroupData($id);
      
      // sprawdzamy, czy w ogóle taka istnieje
      
      if(!$data->exists())
      {
         echo $this->load->view('download_nosuchgroup');
         return;
      }
      
      // tworzymy klucz tymczasowy
      
      list($tempKey, $tempKeyValue) = model('tempkeys')->MakeKey('deletedownloadgroup:' . $id);
      
      echo $this->load->view('download_groupdeletequestion', array('id' => $id, 'tkey' => $tempKey, 'tvalue' => $tempKeyValue));
   }
   
   /*
    * usuwanie grupy
    */
   
   function groupdelete_ok()
   {
      $tempKey = $this->url->segment(1);
      $tempKeyValue = $this->url->segment(2);
      $ID = $this->url->segment(3);
      
      // sprawdzamy, czy z kluczem tymczasowym wszystko w porządku
      
      if(!model('TempKeys')->CheckKey($tempKey, $tempKeyValue, 'deletedownloadgroup:' . $ID))
      {
         echo $this->load->view('error');
         return;
      }
      
      // skoro tak, to usuwamy
      
      model('download')->DeleteGroup($ID);
      
      siteredirect('msg:download_groupdeleted/download');
   }
}
?>