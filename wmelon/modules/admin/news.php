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

class News extends Controller
{  
   /*
    * lista newsów
    */
   
   function index()
   {
      Watermelon::$acceptMessages += array('news_deleted', 'news_posted', 'news_edited');
      
      // pobieramy listę newsów
      
      $newsList = model('news')->getNews();
      
      // sprawdzamy, czy są jakieś newsy
      
      if(!$newsList->exists())
      {
         echo $this->load->view('news_nonews');
         return;
      }
      
      // skoro są, to je wyświetlamy
      
      echo $this->load->view('news_table', array('newsList' => $newsList));
   }
   
   /*
    * formularz nowego newsa
    */
   
   function _new()
   {
      list($tempKey, $tempKeyValue) = model('tempkeys')->MakeKey('newnews', time() + 3600);
      
   	echo $this->load->view('news_new', array('tkey' => $tempKey, 'tvalue' => $tempKeyValue));
   }
   
   /*
    * stworzenie newsa
    */
   
   function Post()
   {
      $tempKey      = $this->url->segment(1);
      $tempKeyValue = $this->url->segment(2);
      
      // sprawdzamy, czy zostały uzupełnione wszystkie pola.
      
      if(empty($_POST['title']) OR empty($_POST['text']))
      {
         echo $this->load->view('allfieldsneeded');
         return;
      }
      
      // sprawdzamy, czy z kluczem tymczasowym wszystko w porządku
      
      if(!model('TempKeys')->CheckKey($tempKey, $tempKeyValue, 'newnews'))
      {
         echo $this->load->view('error');
         return;
      }
      
      // skoro tak, to wysyłamy
      
      model('news')->post(htmlspecialchars($_POST['title']), $_POST['text'], $_SESSION['WTRMLN_UID']);
      
      siteredirect('msg:news_posted/news');
   }
   
   /*
    * formularz edycji newsa
    */
   
   function edit()
   {
      $id = $this->url->segment(1);
      
      $data = model('news')->GetData($id);
      
      // sprawdzamy, czy w ogóle taki istnieje
      
      if(!$data->exists())
      {
         echo $this->load->view('news_nosuch');
         return;
      }
      
      // tworzymy klucz tymczasowy
      
      list($tempKey, $tempKeyValue) = model('tempkeys')->MakeKey($id);
      
      echo $this->load->view('news_edit', array('data' => $data->to_obj(), 'tkey' => $tempKey, 'tvalue' => $tempKeyValue));
   }
   
   /*
    * submit: edycja newsa
    */
   
   function editSubmit()
   {
      $tempKey      = $this->url->segment(1);
      $tempKeyValue = $this->url->segment(2);
      $newsID       = $this->url->segment(3);
      
      // sprawdzamy, czy z kluczem tymczasowym wszystko w porządku
      
      if(!model('TempKeys')->CheckKey($tempKey, $tempKeyValue, $newsID))
      {
         echo $this->load->view('error');
         return;
      }
      
      // skoro tak, to edytujemy
      
      model('news')->Edit($newsID, htmlspecialchars($_POST['title']), $_POST['text']);
      
      siteredirect('msg:news_edited/news');
   }
   
   /*
    * (samo potwierdznie) usunięcia newsa
    */
   
   function delete()
   {
      $id = $this->url->segment(1);
      
      $data = model('news')->GetData($id);
      
      // sprawdzamy, czy w ogóle taki istnieje
      
      if(!$data->exists())
      {
         echo $this->load->view('news_nosuch');
         return;
      }
      
      // tworzymy klucz tymczasowy
      
      list($tempKey, $tempKeyValue) = model('tempkeys')->MakeKey($id);
      
      echo $this->load->view('news_deletequestion', array('id' => $id, 'tkey' => $tempKey, 'tvalue' => $tempKeyValue));
   }
   
   /*
    * usuwanie newsa
    */
   
   function delete_ok()
   {
      $tempKey = $this->url->segment(1);
      $tempKeyValue = $this->url->segment(2);
      $newsID = $this->url->segment(3);
      
      // sprawdzamy, czy z kluczem tymczasowym wszystko w porządku
      
      if(!model('TempKeys')->CheckKey($tempKey, $tempKeyValue, $newsID))
      {
         echo $this->load->view('error');
         return;
      }
      
      // skoro tak, to usuwamy
      
      model('news')->Delete($newsID);
      
      siteredirect('msg:news_deleted/news');
   }
}

?>