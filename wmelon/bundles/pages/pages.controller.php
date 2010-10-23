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
   /*
    * page
    */
   
   public function index_action($pageName)
   {
      if(empty($pageName))
      {
         Watermelon::displayNoPageFoundError();
         return;
      }
      
      // getting post data
      
      $pageData = $this->model->pageData_name($pageName);
      
      if(!$pageData)
      {
         Watermelon::displayNoPageFoundError();
         return;
      }
      
      // displaying (if exists)
      
      $this->pageTitle = $pageData->page_title;
      $this->dontShowPageTitle = true;
      
      $pageData->page_content = Textile::textile($pageData->page_content);
      
      $view = View('page');
      $view->page = $pageData;
      $view->commentsView = Comments::commentsView($pageData->page_id, 'page', 'pages/index/' . $pageName);
      $view->display();
   }
   
   /*
    * shortcut for index_action()
    */
   
   public function _actionHandler($pageName)
   {
      $this->index_action($pageName);
   }
}