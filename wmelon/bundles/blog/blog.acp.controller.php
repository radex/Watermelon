<?php
 //  
 //  This file is part of Watermelon CMS
 //  
 //  Copyright 2010 Radosław Pietruszewski.
 //  
 //  Watermelon CMS is free software: you can redistribute it and/or modify
 //  it under the terms of the GNU General Public License as published by
 //  the Free Software Foundation, either version 3 of the License, or
 //  (at your option) any later version.
 //  
 //  Watermelon CMS is distributed in the hope that it will be useful,
 //  but WITHOUT ANY WARRANTY; without even the implied warranty of
 //  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 //  GNU General Public License for more details.
 //  
 //  You should have received a copy of the GNU General Public License
 //  along with Watermelon CMS. If not, see <http://www.gnu.org/licenses/>.
 //  

/*
 * Blog management
 */

class Blog_Controller extends Controller
{
   /*
    * blog posts table
    */
   
   function index_action()
   {
      $this->pageTitle = 'Lista postów';
      
      $posts = $this->model->posts();
      
      // table configuration
      
      $table = new ACPTable;
      $table->isPagination = false;
      $table->header = array('Tytuł', 'Treść', 'Utworzono', 'Akcje');
      $table->selectedActions[] = array('Usuń', 'blog/delete/');
      $table->selectedActions[] = array('Edytuj', 'blog/edit/');
      
      // adding posts
      
      foreach($posts as $post)
      {
         // content
         
         $content = $post->blogpost_content;
         
         if(strlen($content) > 100)
         {
            $content = substr($content, 0, 100) . ' (...)';
         }
         
         // created
         
         $created = HumanDate($post->blogpost_created); //TODO: + by [author]
         
         //--
         
         $actions = '';
         $actions .= '<a href="$/blog/edit/' . $post->blogpost_id . '">Edytuj</a> | ';
         $actions .= '<a href="$/blog/delete/' . $post->blogpost_id . '">Usuń</a>';
         
         $table->addLine($post->blogpost_id, $post->blogpost_title, $content, $created, $actions);
      }
      
      // displaying
      
      echo $table->generate();
   }
}