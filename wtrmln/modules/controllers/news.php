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

class News extends Controller
{
   function index()
   {
      $this->News = $this->load->model('news');
      
      // pobieramy listę newsów
      
      $newsList = $this->News->getNews();
      
      // sprawdzamy, czy są jakieś newsy
      
      if(!$newsList->exists())
      {
         echo $this->load->view('news_nonews');
         return;
      }
      
      // skoro są, to je wyświetlamy
      
      echo $this->load->view('news_list', array('newsList' => $newsList));
   }
}

?>