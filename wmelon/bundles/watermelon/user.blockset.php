<?php
 //  
 //  This file is part of Watermelon
 //  
 //  Copyright 2010 Radosław Pietruszewski.
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

class User_Blockset extends Blockset
{
   public function card()
   {
      //FIXME: fix Loader, so that you can properly load views from blocksets and extensions
      
      $view = View('watermelon/userCard', true);
      $view->isLogged = Users::isLogged();
      $view->userData = Users::userData();
      $view->userData->lastseen = date('d.m.Y H:i:s', $view->userData->lastseen);
      $view->privileges = Users::privileges();
      $view->display();
   }
}