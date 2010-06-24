<?php
 //  
 //  Watermelon CMS
 //  
 //  Copyright 2009 Radosław Pietruszewski.
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
   
   /*
    * lista plików w grupie
    */
   
   function Group()
   {
      Watermelon::addmsgs('download_filedeleted', 'download_fileposted', 'download_fileedited');
      
      $group = $this->url->segment(1);
      
      $files = model('download')->GetFiles($group);
      
      if(!$files->exists())
      {
         echo $this->load->view('download_nofiles', array('gid' => $group));
         return;
      }
      
      echo $this->load->view('download_filestable', array('files' => $files, 'gid' => $group));
   }
   
   /*
    * formularz nowego pliku
    */
   
   function newfile()
   {
      $id = $this->url->segment(1);
      
      $data = model('download')->GroupData($id);
      
      // sprawdzamy, czy w ogóle taka istnieje
      
      if(!$data->exists())
      {
         echo $this->load->view('download_nosuchgroup');
         return;
      }
      
      list($tempKey, $tempKeyValue) = model('tempkeys')->MakeKey('newdownloadfile:' . $id, time() + 3600);
      
      echo $this->load->view('download_filenew', array('tkey' => $tempKey, 'tvalue' => $tempKeyValue, 'id' => $id));
   }
   
   /*
    * stworzenie pliku
    */
   
   function postfile()
   {
      $tempKey      = $this->url->segment(1);
      $tempKeyValue = $this->url->segment(2);
      $id           = $this->url->segment(3);
      
      // sprawdzamy, czy zostały uzupełnione wszystkie pola.
      
      if(empty($_POST['file']) || empty($_POST['link']) || empty($_POST['description']) || empty($_POST['size']) || empty($_POST['unit']))
      {
         echo $this->load->view('allfieldsneeded');
         return;
      }
      
      // sprawdzamy, czy z kluczem tymczasowym wszystko w porządku
      
      if(!model('TempKeys')->CheckKey($tempKey, $tempKeyValue, 'newdownloadfile:' . $id))
      {
         echo $this->load->view('error');
         return;
      }
      
      // skoro tak, to wysyłamy
      
      model('download')->postfile(htmlspecialchars($_POST['file']), $_POST['link'], $_POST['description'], floatval($_POST['size']) . ' ' . $_POST['unit'], $id);
      siteredirect('msg:download_fileposted/download/group/' . $id);
   }
   
   /*
    * formularz edycji pliku
    */
   
   function editfile()
   {
      $id = $this->url->segment(1);
      
      $data = model('download')->FileData($id);
      
      // sprawdzamy, czy w ogóle taki istnieje
      
      if(!$data->exists())
      {
         echo $this->load->view('download_nosuchfile');
         return;
      }
      
      // tworzymy klucz tymczasowy
      
      list($tempKey, $tempKeyValue) = model('tempkeys')->MakeKey('editdownloadfile:' . $id, time() + 3600);
      
      $data = $data->to_obj();
      $data->size  = explode(' ', $data->size);
      $data->rsize = $data->size[0];
      $data->unit  = $data->size[1];
      
      echo $this->load->view('download_fileedit', array('data' => $data, 'tkey' => $tempKey, 'tvalue' => $tempKeyValue));
   }
   
   /*
    * submit: edycja pliku
    */
   
   function editfile_submit()
   {
      $tempKey      = $this->url->segment(1);
      $tempKeyValue = $this->url->segment(2);
      $ID           = $this->url->segment(3);
      
      // sprawdzamy, czy z kluczem tymczasowym wszystko w porządku
      
      if(!model('TempKeys')->CheckKey($tempKey, $tempKeyValue, 'editdownloadfile:' . $ID))
      {
         echo $this->load->view('error');
         return;
      }
      
      // skoro tak, to edytujemy
      
      $gid = model('download')->editfile(htmlspecialchars($_POST['file']), $_POST['link'], $_POST['description'], floatval($_POST['size']) . ' ' . $_POST['unit'], $ID);
      
      siteredirect('msg:download_fileedited/download/group/' . $gid);
   }
   
   /*
    * (samo potwierdznie) usunięcia pliku
    */
   
   function deletefile()
   {
      $id = $this->url->segment(1);
      
      $data = model('download')->FileData($id);
      
      // sprawdzamy, czy w ogóle taki istnieje
      
      if(!$data->exists())
      {
         echo $this->load->view('download_nosuchfile');
         return;
      }
      
      // tworzymy klucz tymczasowy
      
      list($tempKey, $tempKeyValue) = model('tempkeys')->MakeKey('deletedownloadfile:' . $id);
      
      echo $this->load->view('download_filedeletequestion', array('id' => $id, 'tkey' => $tempKey, 'tvalue' => $tempKeyValue));
   }
   
   /*
    * usuwanie pliku
    */
   
   function filedelete_ok()
   {
      $tempKey = $this->url->segment(1);
      $tempKeyValue = $this->url->segment(2);
      $ID = $this->url->segment(3);
      
      // sprawdzamy, czy z kluczem tymczasowym wszystko w porządku
      
      if(!model('TempKeys')->CheckKey($tempKey, $tempKeyValue, 'deletedownloadfile:' . $ID))
      {
         echo $this->load->view('error');
         return;
      }
      
      // skoro tak, to usuwamy
      
      $gid = model('download')->DeleteFile($ID);
      
      siteredirect('msg:download_filedeleted/download/group/' . $gid);
   }
}
?>