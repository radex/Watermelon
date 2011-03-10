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
 * Blog Model
 */

class Blog_Model extends Model
{
   /*
    * public DBResult allPosts([string $scope])
    * 
    * Returns list of all posts
    * 
    * string $scope:
    *    null (default) - all posts
    *    'all'          - drafts and published (not really all)
    *    'drafts'       - drafts (not published posts)
    *    'published'    - published posts
    *    'trash'        - posts moved to trash
    */
   
   public function allPosts($scope = null)
   {
      $query = Query::select('blogposts')->order('id DESC');
      
      // scope
      
      switch($scope)
      {
         case 'all':
            $query = $query->where('status IN("published", "draft")');
         break;
         
         case 'drafts':
            $query = $query->where('status', 'draft');
         break;
         
         case 'published':
            $query = $query->where('status', 'published');
         break;
         
         case 'trash':
            $query = $query->where('status', 'trash');
         break;
      }
      
      //--
      
      return $query->act();
   }
   
   /*
    * public object counts()
    * 
    * Returns posts counts:
    * ->all       - for published/drafts
    * ->trash     - for posts moved to trash
    * ->drafts    - for drafts
    * ->published - for published posts
    */
   
   public function counts()
   {
      $counts->all       = Query::select('blogposts')->where('status IN("published", "draft")')->act()->rows;
      $counts->trash     = Query::select('blogposts')->where('status', 'trash')->act()->rows;
      $counts->drafts    = Query::select('blogposts')->where('status', 'draft')->act()->rows;
      $counts->published = Query::select('blogposts')->where('status', 'published')->act()->rows;
      
      return $counts;
   }
   
   /*
    * public DBResult posts(int $page)
    * 
    * List of posts (11 posts, starting from $page)
    * 
    * There are 10 posts for page, but 11 are selected, so that we know if there's another page
    * 
    * Note that only published posts are selected
    */
   
   public function posts($page)
   {
      $page = (int) $page - 1;
      
      return Query::select('blogposts')->where('status', 'published')->order('id DESC')->limit(11)->offset($page * 10)->act();
   }
   
   /*
    * public object postData_id(int $postID)
    * 
    * Data of a post (or FALSE if doesn't exist)
    */
   
   public function postData_id($id)
   {
      return DB::select('blogposts', (int) $id);
   }
   
   /*
    * public object postData_name(int $name)
    * 
    * Data of a post (or FALSE if doesn't exist)
    */
   
   public function postData_name($name)
   {
      return Query::select('blogposts')->where('name', (string) $name)->act()->fetch();
   }
   
   /*
    * public int postPost(bool $publish, string $title, string $content, string $summary, bool $allowComments)
    * 
    * Posts a post with given data, as currently logged user and with current time and returns its ID
    * 
    * bool $publish - if true, post is published, otherwise it's saved as draft
    */
   
   public function postPost($publish, $title, $content, $summary, $allowComments)
   {
      // status
      
      if($publish)
      {
         $status = 'published';
      }
      else
      {
         $status = 'draft';
      }
      
      // summary
      
      $summary = (string) $summary;
      
      if(empty($summary))
      {
         $summary = null;
      }
      
      // generating Atom ID
      
      $atomID = Watermelon::$config->atomID . $name . time() . mt_rand();
      $atomID = sha1($atomID);
      
      // inserting
      
      $id = DB::insert('blogposts', array
         (
            'name'          =>          self::generateName($title),
            'title'         => (string) $title,
            
            'content'       => (string) $content,
            'summary'       =>          $summary,
            
            'author'        =>          Users::userData()->id,
            'published'     =>          time(),
            'updated'       =>          time(),
            
            'atomID'        =>          $atomID,
            'allowComments' => (bool)   $allowComments,
            'status'        =>          $status,
         ));
      
      // updating feed
      
      self::updateFeed();
      
      // returning post ID
      
      return $id;
   }
   
   /*
    * public void editPost(int $id, string $title, string $content, string $summary, bool $allowComments)
    * 
    * Edits $id post, setting given data
    */
   
   public function editPost($id, $title, $content, $summary, $allowComments)
   {
      // summary
      
      $summary = (string) $summary;
      
      if(empty($summary))
      {
         $summary = null;
      }
      
      // updating
      
      DB::update('blogposts', (int) $id, array
         (
            'title'         => (string) $title,
            'content'       => (string) $content,
            'summary'       =>          $summary,
            'updated'       =>          time(),
            'allowComments' => (bool)   $allowComments,
         ));
      
      self::updateFeed();
   }
   
   /*
    * public void publish(int[] $ids)
    * 
    * Changes status of posts to published and updates published/updates dates
    */
   
   public function publish($ids)
   {
      Query::update('blogposts')->set('published', time())->where('id', 'in', $ids)->act();
      
      self::changeStatus($ids, 'published');
   }
   
   /*
    * public void changeStatus(int[] $ids, enum $status)
    * 
    * Changes status of posts to $status
    * 
    * enum $status = {'published','draft','trash'}
    */
   
   public function changeStatus($ids, $status)
   {
      Query::update('blogposts')->set('status', $status, 'updated', time())->where('id', 'in', $ids)->act();
      
      self::updateFeed();
   }
   
   /*
    * public void deletePosts(int[] $ids)
    * 
    * Deletes posts
    */
   
   public function deletePosts(array $ids)
   {
      DB::delete('blogposts', $ids);
      
      foreach($ids as $id)
      {
         Comments_Model::deleteCommentsFor($id, 'blogpost');
      }
      
      self::updateFeed();
   }
   
   /*
    * public void updateFeed()
    * 
    * Generates Atom feed from 20 last blog posts, and saves it to cache file
    */
   
   public function updateFeed()
   {
      $wmelon = Watermelon::$config;
      
      // composing <feed>
      
      $feed = new SimpleXMLElement('<?xml version="1.0" encoding="utf-8"?><feed xmlns="http://www.w3.org/2005/Atom"/>');
      
      $feed->id      = $wmelon->atomID;
      $feed->updated = date(DATE_ATOM);
      $feed->title   = strip_tags($wmelon->siteName);
      
      if(!empty($wmelon->siteSlogan))
      {
         $feed->subtitle = strip_tags($wmelon->siteSlogan);
      }
      
      $feed->author->name = Users::userData()->nick;
      $feed->author->uri  = WM_SiteURL;
      
      $feed->link['rel'] = 'self';
      $feed->link['href'] = WM_SiteURL . 'feed.atom';
      
      // $feed->rights
      
      $feed->generator = 'Watermelon';
      
      // adding blog posts
      
      $posts = Query::select('blogposts')->where('status', 'published')->order('id DESC')->limit(20)->act();
      
      foreach($posts as $post)
      {
         $postElement = $feed->addChild('entry');
         
         $postElement->title     = $post->title;
         $postElement->id        = $post->atomID;
         $postElement->published = date(DATE_ATOM, $post->published);
         $postElement->updated   = date(DATE_ATOM, $post->updated);
         
         $postElement->link['rel']  = 'alternate';
         $postElement->link['href'] = WM_SiteURL . date('Y/m', $post->published) . '/' . $post->name;
         
         $postElement->content['type'] = 'html';
         $postElement->content = Textile::textile($post->content);
         
         if(!empty($post->summary))
         {
            $postElement->summary['type'] = 'html';
            $postElement->summary = Textile::textile($post->summary);
         }
      }
      
      // save to file
      
      file_put_contents(WM_Cache . 'feed.atom', $feed->asXML());
   }
   
   /**************************************************************************/
   
   /*
    * Auxiliary methods
    */
   
   /*
    * private string generateName(string $title)
    * 
    * Generates blog post name (part of URL) from its title
    */
   
   private function generateName($title)
   {
      $name = (string) $title;
      
      // deletes all necessary characters
      
      $name = str_replace(array('?', '/', '#', '&', "'", '"'), '', $name);
      $name = str_replace(':', ' -', $name);
      $name = str_replace(' ', '_', $name);
      
      // if already exists, generating unique
      
      if(Query::select('blogposts')->where('name', $name)->act()->exists)
      {
         $i = 2;
         
         do
         {
            $name2 = $name . '_(' . $i . ')';
            
            $i++;
         }
         while(Query::select('blogposts')->where('name', $name2)->act()->exists);
         
         $name = $name2;
      }
      
      //--
      
      return $name;
   }
}