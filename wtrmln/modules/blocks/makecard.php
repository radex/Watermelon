<?php
 //  
 //  Watermelon CMS
 //  
 //  Copyright 2009 Radosław Pietruszewski.
 //  
 //  This program is free software: you can redistribute it and/or modify
 //  it under the terms of the GNU General Public License as published by
 //  the Free Software Foundation, either version 3 of the License, or
 //  (at your option) any later version.
 //  
 //  This program is distributed in the hope that it will be useful,
 //  but WITHOUT ANY WARRANTY; without even the implied warranty of
 //  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 //  GNU General Public License for more details.
 //  
 //  You should have received a copy of the GNU General Public License
 //  along with this program.  If not, see <http://www.gnu.org/licenses/>.
 //  

class BlockMakeCard extends Block
{
   function block()
   {
      $uid = $this->data;
      
		$userData = Controller::$_user->getData($uid);
		
		$card .= '<cite><a href="$/ludzie/user/' . $userData->nick . '/">' . $userData->nick . '</a></cite>';
		
      if($userData->lastseen >= time() - 500)
      {
         $card .= ' [online]';
      }
      
      $card .= '<br>';
      
      $card .= '<strong>Postów: </strong>' . $userData->posts;
      
      $card .= '<br>';
      
      if($userData->lastseen < time() - 500)
      {
         if($userData->lastseen != 0)
         {
            $card .= '<strong>Ostatnio widziany: </strong>' . plDate($userData->lastseen);
            
            $card .= '<br>';
         }
      }
		
		echo $card;
   }
}

?>