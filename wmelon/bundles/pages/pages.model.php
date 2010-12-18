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
      return DBQuery::select('pages')->orderBy('id', true)->act();
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
      return DBQuery::select('pages')->where('name', (string) $name)->act()->fetchObject();
   }
   
   /*
    * public void postPage(string $title, string $name, string $content)
    * 
    * Posts a page with given data, as currently logged user and with current time
    * 
    * If $name is not given, automatic will be generated from title
    */
   
   public function postPage($title, $name, $content)
   {
      if(empty($name))
      {
         $name = $this->generateName($title);
      }
      else
      {
         $name = $this->filterName($name);
      }
      
      DB::insert('pages', array
         (
            'name'    => (string) $name,
            'title'   => (string) $title,
            'content' => (string) $content,
            'author'  => Auth::userData()->id,
            'created' => time(),
            'updated' => time(),
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
            'name'    => $this->filterName($name),
            'content' => (string) $content,
            'updated' => time(),
         ));
   }
   
   /*
    * public void deletePages(int[] $ids)
    * 
    * Deletes pages with given ID-s
    */
   
   public function deletePages(array $ids)
   {
      DB::delete('pages', $ids);
      
      foreach($ids as $id)
      {
         Model('comments')->deleteCommentsFor($id, 'page');
      }
   }
   
   /*
    * private string generateName(string $title)
    * 
    * Generates page name (part of URL) from its title
    */
   
   private function generateName($title)
   {
      $name = (string) $title;
      
      $name = $this->filterName($name);
      $name = str_replace('/', '', $name);
      
      // if already exists, generating unique
      
      if(DBQuery::select('pages')->where('name', $name)->act()->exists)
      {
         $i = 2;
         
         do
         {
            $name2 = $name . '_(' . $i . ')';
            
            $i++;
         }
         while(DBQuery::select('pages')->where('name', $name2)->act()->exists);
         
         $name = $name2;
      }
      
      //--
      
      return $name;
   }
   
   /*
    * private function filterName(string $name)
    * 
    * Filters page name (part of URL) - strips from illegal characters
    */
   
   private function filterName($name)
   {
      $name = (string) $name;
      
      $name = str_replace(array('?', '#', '&'), '', $name);
      $name = str_replace(':', ' -', $name);
      $name = str_replace(' ', '_', $name);
      
      return $name;
   }
}