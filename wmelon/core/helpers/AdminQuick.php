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
      
      $message = $questionClosure($ids, Watermelon::$controllerObject);
      
      echo QuestionBox($message, $controller . '/deleteSubmit/' . implode(',', $ids) . '/' . $backPage);
   }
   
   /*
    * public static void deleteSubmit(string $ids, string $backPage, string $controller, $deletingClosure, $messageClosure)
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
      
      foreach($ids as $id)
      {
         $deletingClosure($id, Watermelon::$controllerObject->model);
      }
      
      // redirecting
      
      $message = $messageClosure(count($ids));
      
      Watermelon::$controllerObject->addMessage('tick', $message);
      
      $backPage = base64_decode($backPage);
      $backPage = empty($backPage) ? $controller : $backPage;
      
      SiteRedirect($backPage);
   }
}