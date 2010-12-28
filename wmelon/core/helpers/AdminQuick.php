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
 * Library for quicker creating of ACP controllers
 */

class AdminQuick
{
   /*
    * public static void delete(string $ids, string $backPage, string $controller, $questionClosure)
    * 
    * Generates delete page for ACP controllers
    * 
    * string $ids        - ID-s string passed by URL
    * string $backPage   - base64ed page to get back to after deletion, passed by URL
    * string $controller - name of controller, e.g. 'blog'
    * 
    * string $questionClosure(array $ids, Model $model) -
    *    Closure generating question message
    *    
    *    array $ids   - array with ID-s to be deleted
    *    Model $model - model of currently running controller
    */
   
   /*
    * Usage:
   
   function delete_action($ids, $backPage)
   {
      AdminQuick::delete($ids, $backPage, '__controller__',
         function($ids, $model)
         {
            return '__message__';
         });
      
   }
   
    *
    */
   
   public static function delete($ids, $backPage, $controller, $questionClosure)
   {
      $ids = IDs($ids);
      
      // if empty
      
      if(empty($ids))
      {
         SiteRedirect($controller);
      }
      
      // showing question
      
      $message = $questionClosure($ids, Watermelon::$controller->model);
      
      echo QuestionBox($message, $controller . '/deleteSubmit/' . implode(',', $ids) . '/' . $backPage);
   }
   
   /*
    * public static void deleteSubmit(string $ids, string $backPage, string $controller, $deletingClosure, $messageClosure)
    * 
    * Generates delete submit page for ACP controllers
    * 
    * string $ids        - ID-s string passed by URL
    * string $backPage   - base64ed page to get back to after deletion, passed by URL
    * string $controller - name of controller, e.g. 'blog'
    * 
    * void $deletingClosure(int[] $ids, Model $model) -
    *    Closure deleting $ids items
    *    
    *    int[] $ids[] - array of ID-s of items to be deleted
    *    Model $model - model of currently running controller
    * 
    * string $messageClosure(int $count)
    *    Closure generating message that items have been deleted
    *    
    *    int $count - number of deleted items
    */
    
    /*
     * Usage:
    
    function deleteSubmit_action($ids, $backPage)
    {
       AdminQuick::deleteSubmit($ids, $backPage, '__controller__',
          function($ids, $model)
          {
             $model->__deleteMethod__($ids);
          },
          function($count)
          {
             return '__message__';
          });
    }
    
     * 
     */
   
   public static function deleteSubmit($ids, $backPage, $controller, $deletingClosure, $messageClosure)
   {
      $ids = IDs($ids);
      
      // if empty
      
      if(empty($ids))
      {
         SiteRedirect($controller);
      }
      
      // deleting
      
      $deletingClosure($ids, Watermelon::$controller->model);
      
      // redirecting
      
      $message = $messageClosure(count($ids));
      
      Watermelon::$controller->addMessage('tick', $message);
      
      $backPage = base64_decode($backPage);
      $backPage = empty($backPage) ? $controller : $backPage;
      
      SiteRedirect($backPage);
   }
}