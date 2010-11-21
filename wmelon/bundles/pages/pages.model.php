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
 * Pages model
 */

class Pages_Model extends Model
{
   /*
    * public DBResult pages()
    * 
    * List of pages
    */
   
   public function pages()
   {
      return DBQuery::select('pages')->orderBy('id', true)->execute();
   }
   
   /*
    * public object pageData_id(int $postID)
    * 
    * Data of a page (by ID) (or FALSE if doesn't exist)
    */
   
   public function pageData_id($id)
   {
      return DB::select('pages', (int) $id);
   }
   
   /*
    * public object pageData_name(string $pageName)
    * 
    * Data of a page (by name) (or FALSE if doesn't exist)
    */
   
   public function pageData_name($name)
   {
      return DBQuery::select('pages')->where('name', (string) $name)->execute()->fetchObject();
   }
   
   /*
    * public void postPage(string $title, string $name, string $content)
    * 
    * Posts a page with given data, as currently logged user and with current time
    */
   
   public function postPage($title, $name, $content)
   {
      DB::insert('pages', array
         (
            'author'  => Auth::userData()->id,
            'created' => time(),
            'title'   => (string) $title,
            'name'    => (string) $name,
            'content' => (string) $content,
         ));
   }
   
   /*
    * public void editPage(int $id, string $title, string $name, string $content)
    * 
    * Edits $id page, setting given data
    */
   
   public function editPage($id, $title, $name, $content)
   {
      DB::update('pages', (int) $id, array
         (
            'title'   => (string) $title,
            'name'    => (string) $name,
            'content' => (string) $content
         ));
   }
   
   /*
    * public void deletePage(int $id)
    * 
    * Deletes a page with given ID
    */
   
   public function deletePage($id)
   {
      DB::delete('pages', (int) $id);
   }
}