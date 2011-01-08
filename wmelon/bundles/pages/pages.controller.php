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
 * Pages controller
 */

class Pages_Controller extends Controller
{
   function index_action()
   {
      Watermelon::displayNoPageFoundError();
   }
   
   /*
    * page
    */
   
   function _controllerHandler($pageName)
   {
      // getting post data
      
      $pageData = $this->model->pageData_name($pageName);
      
      if(!$pageData)
      {
         Watermelon::displayNoPageFoundError();
         return;
      }
      
      // displaying (if exists)
      
      $id = $pageData->id;
      
      $pageData->content = Textile::textile($pageData->content);
      
      $this->pageTitle = $pageData->title;
      $this->dontShowPageTitle = true;
      
      $view = View('page');
      $view->page = $pageData;
      $view->commentsView = Comments::commentsView($pageData->id, 'page', '#/' . $pageName, (bool) $pageData->allowComments);
      
      $view->editHref = '%/pages/edit/' . $id . '/backTo:site';
      $view->deleteHref = '%/pages/delete/' . $id;
      
      $view->display();
   }
}