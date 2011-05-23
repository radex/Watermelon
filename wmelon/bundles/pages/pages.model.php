<?php
 //  
 //  This file is part of Watermelon
 //  
 //  Copyright 2010-2011 Radosław Pietruszewski.
 //  
 //  Watermelon is free software: you can redistribute it and/or modify
 //  it under the terms of the GNU General Public License as published by
 //  the Free Software Foundation, either version 3 of the License, or
 //  (at your option) any later version.
 //  
 //  Watermelon is distributed in the hope that it will be useful,
 //  but WITHOUT ANY WARRANTY; without even the implied warranty of
 //  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 //  GNU General Public License for more details.
 //  
 //  You should have received a copy of the GNU General Public License
 //  along with Watermelon. If not, see <http://www.gnu.org/licenses/>.
 //  

/*
 * Pages model
 */

class Pages_Model extends Model
{
   /*
    * getting
    */
   
   /*
    * public DBResult pages([$scope])
    * 
    * List of pages
    * 
    * $scope:
    *    null (default) - all pages
    *    'published'    - published pages
    *    'trash'        - pages moved to trash
    */
   
   public function pages($scope)
   {
      $q = Query::select('pages')->order('id DESC');
      
      // scope
      
      switch($scope)
      {
         case 'published':
            $q = $q->where('status', 'published');
         break;
         
         case 'trash':
            $q = $q->where('status', 'trash');
         break;
      }
      
      //--
      
      return $q->act();
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
      return Query::select('pages')->where('name', (string) $name)->act()->fetch();
   }
   
   /*
    * public object counts()
    * 
    * Returns pages counts:
    * ->published - for published pages
    * ->trash     - for pages moved to trash
    */
   
   public function counts()
   {
      $counts->published = Query::select('pages')->where('status', 'published')->act()->rows;
      $counts->trash     = Query::select('pages')->where('status', 'trash')->act()->rows;
      
      return $counts;
   }
   
   /**************************************************************************/
   
   /*
    * public int postPage(string $title, string $name, string $content)
    * 
    * Posts a page with given data, as currently logged user and with current time
    * 
    * If $name is not given, automatic will be generated from title
    * 
    * Returns its ID
    */
   
   public function postPage($title, $name, $content)
   {
      // name
      
      if(empty($name))
      {
         $name = self::generateName($title);
      }
      else
      {
         $name = GenerateURLName($name);
      }
      
      // inserting
      
      return DB::insert('pages', array
         (
            'name'    => (string) $name,
            'title'   => (string) $title,
            'content' => (string) $content,
            'author'  => 1,                  // admin account
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
            'name'    => GenerateURLName($name),
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
         Comments_Model::deleteCommentsFor($id, 'page');
      }
   }
   
   /*
    * public void changeStatus(int[] $ids, enum $status)
    * 
    * Changes status of pages to $status
    * 
    * enum $status = {'published','trash'}
    */
   
   public function changeStatus($ids, $status)
   {
      Query::update('pages')->set('status', $status, 'updated', time())->where('id', 'in', $ids)->act();
   }
   
   /**************************************************************************/
   
   /*
    * Auxiliary methods
    */
   
   /*
    * private string generateName(string $title)
    * 
    * Generates page name (part of URL) from its title
    */
   
   private function generateName($title)
   {
      $name = GenerateURLName($title);
      $name = str_replace('/', '', $name);
      
      // if already exists, generating unique
      
      if(Query::select('pages')->where('name', $name)->act()->exists)
      {
         $i = 2;
         
         do
         {
            $name2 = $name . '_(' . $i . ')';
            
            $i++;
         }
         while(Query::select('pages')->where('name', $name2)->act()->exists);
         
         $name = $name2;
      }
      
      //--
      
      return $name;
   }
}