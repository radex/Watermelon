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

class Pages extends Controller
{
   function Index()
   {
      // wyciągamy segmenty URL-a (nazwa page'a) i mierzymy ich ilość
      
      $page = URL::$segments;
      
      $segments = count($page);
      
      // jeśli brak segmentów
      
      if($segments == 0)
      {
         $this->e404('');
         return;
      }
      
      // jeśli $page jest stringiem zamieniamy go na tablicę z jedną wartością
      
      if(is_string($page))
      {
         $page = array($page);
      }
      
      // łączymy w łańcuch
      
      $page = implode('/', $page);
      
      // sprawdzamy czy istnieje
      
      $Pages = $this->load->model('Pages');
      
      $data = $Pages->getData($page);
      
      if($data->exists())
      {
         $data = $data->to_obj();
         
         // ustawiamy tytuł
         
         setH1($data->title);
         
         $content = $data->content;
         
         // przetwarzamy pseudotagi
         
         $content = ViewTags::Process($content);
         
         // wykonujemy :)
         
         eval('?>' . $content . '<?php ');
         
      }
      else
      {
         $this->e404($page);
      }
   }
   
   private function e404($pageName = '')
   {
      setH1('Błąd 404 : Nie odnaleziono');
      
      echo $this->load->view('e404', array('pageName' => $pageName));
   }
}
?>