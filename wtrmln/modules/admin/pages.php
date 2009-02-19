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

class Pages extends Controller
{  
   /*
    * lista stron
    */
   
   function Index()
   {
      Watermelon::$acceptMessages += array('pages_posted', 'pages_deleted', 'pages_edited');
      
      // pobieramy listę stron
      
      $pagesList = model('pages')->getPages();
      
      // sprawdzamy, czy są jakieś strony
      
      if(!$pagesList->exists())
      {
         echo $this->load->view('pages_nopages');
         return;
      }
      
      // skoro są, to je wyświetlamy
      
      echo $this->load->view('pages_table', array('pagesList' => $pagesList));
   }
   
   /*
    * formularz nowej strony
    */
   
   function _new()
   {
      list($tempKey, $tempKeyValue) = model('tempkeys')->MakeKey('newpage', time() + 3600);
      
      echo $this->load->view('pages_new', array('tkey' => $tempKey, 'tvalue' => $tempKeyValue));
   }
   
   /*
    * stworzenie strony
    */
   
   function Post()
   {
      $tempKey      = $this->url->segment(1);
      $tempKeyValue = $this->url->segment(2);
      
      // sprawdzamy, czy zostały uzupełnione wszystkie pola.
      
      if(empty($_POST['title']) OR empty($_POST['text']) OR empty($_POST['name']))
      {
         echo $this->load->view('allfieldsneeded');
         return;
      }
      
      // sprawdzamy, czy z kluczem tymczasowym wszystko w porządku
      
      if(!model('TempKeys')->CheckKey($tempKey, $tempKeyValue, 'newpage'))
      {
         echo $this->load->view('error');
         return;
      }
      
      // skoro tak, to wysyłamy
      
      model('pages')->post(htmlspecialchars($_POST['title']), $_POST['name'], $_POST['text']);
      
      redirect(site_url('msg:pages_posted/pages'));
   }
   
   /*
    * formularz edycji strony
    */
   
   function edit()
   {      
      $id = $this->url->segment(1);
      
      $data = model('pages')->GetDataByID($id);
      
      // sprawdzamy, czy w ogóle taka istnieje
      
      if(!$data->exists())
      {
         echo $this->load->view('pages_nosuch');
         return;
      }
      
      // tworzymy klucz tymczasowy
      
      list($tempKey, $tempKeyValue) = model('tempkeys')->MakeKey('edit:' . $id, time() + 3600);
      
      echo $this->load->view('pages_edit', array('data' => $data->to_obj(), 'tkey' => $tempKey, 'tvalue' => $tempKeyValue));
   }
   
   /*
    * submit: edycja strony
    */
   
   function editSubmit()
   {
      $tempKey      = $this->url->segment(1);
      $tempKeyValue = $this->url->segment(2);
      $pageID       = $this->url->segment(3);
      
      // sprawdzamy, czy z kluczem tymczasowym wszystko w porządku
      
      if(!model('TempKeys')->CheckKey($tempKey, $tempKeyValue, 'edit:' . $pageID))
      {
         echo $this->load->view('error');
         return;
      }
      
      // skoro tak, to edytujemy
      
      model('pages')->Edit($pageID, htmlspecialchars($_POST['title']), $_POST['name'], $_POST['text']);
      
      redirect(site_url('msg:pages_edited/pages'));
   }
   
   /*
    * (samo potwierdznie) usunięcia strony
    */
   
   function delete()
   {
      $id = $this->url->segment(1);
      
      $data = model('pages')->GetDataByID($id);
      
      // sprawdzamy, czy w ogóle taka istnieje
      
      if(!$data->exists())
      {
         echo $this->load->view('pages_nosuch');
         return;
      }
      
      // tworzymy klucz tymczasowy
      
      list($tempKey, $tempKeyValue) = model('tempkeys')->MakeKey('delete:' . $id);
      
      echo $this->load->view('pages_deletequestion', array('id' => $id, 'tkey' => $tempKey, 'tvalue' => $tempKeyValue));
   }
   
   /*
    * usuwanie strony
    */
   
   function delete_ok()
   {
      $tempKey = $this->url->segment(1);
      $tempKeyValue = $this->url->segment(2);
      $pageID = $this->url->segment(3);
      
      // sprawdzamy, czy z kluczem tymczasowym wszystko w porządku
      
      if(!model('TempKeys')->CheckKey($tempKey, $tempKeyValue, 'delete:' . $pageID))
      {
         echo $this->load->view('error');
         return;
      }
      
      // skoro tak, to usuwamy
      
      model('pages')->Delete($pageID);
      
      redirect(site_url('msg:pages_deleted/pages'));
   }
}
?>