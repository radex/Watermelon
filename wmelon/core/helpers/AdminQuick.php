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
 * Library for quicker creating of ACP controllers
 */

class AdminQuick
{
   /*
    * public static void bulkAction(string $actionName, string $controller, string $ids, string $backPage, $questionClosure)
    * 
    * Generates question page for bulk action (e.g. deleting) for ACP controllers
    * 
    * string $actionName - name of action, e.g. 'delete'; have to be the same as method name (without 'action') and as submit method page (without '_submit_action')
    * string $controller - name of controller, e.g. 'blog'
    * string $ids        - ID-s string passed by URL
    * string $backPage   - base64ed page to get back to after action, passed by URL
    * 
    * string $questionClosure(array $ids, Model $model) -
    *    Anonymous function generating question message
    *    
    *    array $ids   - array with ID-s to be passed
    *    Model $model - model of currently running controller
    */
   
   /*
    * Usage:
   
   function [action]_action($ids, $backPage)
   {
      AdminQuick::bulkAction('[action]', '[controller]', $ids, $backPage,
         function($ids, $model)
         {
            return '[message]';
         });
      
   }
   
    *
    */
   
   public static function bulkAction($actionName, $controller, $ids, $backPage, $questionClosure)
   {
      $ids = IDs($ids);
      
      // if empty
      
      if(empty($ids))
      {
         SiteRedirect($controller);
      }
      
      // showing question
      
      $message = $questionClosure($ids, Watermelon::$controller->model);
      
      echo QuestionBox($message, $controller . '/' . $actionName . '_submit/' . implode(',', $ids) . '/' . $backPage);
   }
   
   /*
    * public static void bulkActionSubmit(string $controller, string $ids, string $backPage, $actionClosure[, $messageClosure])
    * 
    * Performs bulk action for ACP controllers
    * 
    * string $controller - name of controller, e.g. 'blog'
    * string $ids        - ID-s string passed by URL
    * string $backPage   - base64ed page to get back to after action, passed by URL
    * 
    * void $actionClosure(int[] $ids, Model $model) -
    *    Anonymous function performing action on $ids
    *    
    *    int[] $ids   - array of ID-s of items to perform action on
    *    Model $model - model of currently running controller
    * 
    * string $messageClosure(int $count)
    *    Anonymous function generating message that action has been performed
    *    
    *    If not specified, no message is displayed
    *    
    *    int $count - number of items which action was performed on
    */
    
    /*
     * Usage:
    
    function [action]_submit_action($ids, $backPage)
    {
       AdminQuick::bulkActionSubmit('[controller]', $ids, $backPage,
          function($ids, $model)
          {
             $model->[actionMethod]($ids);
          },
          function($count)
          {
             return '[message]';
          });
    }
    
     * 
     */
   
   public static function bulkActionSubmit($controller, $ids, $backPage, $actionClosure, $messageClosure = null)
   {
      $ids = IDs($ids);
      
      // if empty
      
      if(empty($ids))
      {
         SiteRedirect($controller);
      }
      
      // deleting
      
      $actionClosure($ids, Watermelon::$controller->model);
      
      // message
      
      if($messageClosure !== null)
      {
         $message = $messageClosure(count($ids));
      
         Watermelon::$controller->addMessage('tick', $message);
      }
      
      // redirecting
      
      $backPage = base64_decode($backPage);
      $backPage = empty($backPage) ? $controller : $backPage;
      
      SiteRedirect($backPage);
   }
}