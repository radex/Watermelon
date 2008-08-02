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

class Pages extends Controller
{
   function Pages()
   {
      parent::Controller();
   }
   
   function Index()
   {
      // wyciągamy człony ścieżki (nazwa page'a)
      
      $page = URL::$segments;
      
      // łączymy w łańcuch
      
      $page = implode('/', $page);
      
      // sprawdzamy czy istnieje
      
      $Pages = $this->load->model('Pages');
      
      $data = $Pages->getData($page);
      
      if($data->num_rows() > 0)
      {
         $data = $data->to_obj();
         setH1($data->title);
         
         $content = $data->content;
         
         // w razie gdyby nie można było używać <? i <?=
         
         if(@ini_get('short_open_tag') === FALSE)
         {
            $content = str_replace('<?=', '<?php echo ', $content);
            $content = str_replace('<?',  '<?php', $content);
         }
         
         // wykonujemy :)
         
         eval('?>' . $content);
         
      }
      else
      {
         $this->e404();
      }
   }
   
   function e404()
   {
      setH1('Błąd 404');
      echo 'Błąd czterysta cztery - przykro mnie bardzo :P';
   }
}
?>