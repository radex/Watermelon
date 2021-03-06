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

/*
 * interface ACPInfo
 * 
 * Interface for classes conveying information about Admin Control Panel controllers in bundle
 */

interface ACPInfo
{
   /*
    * public array info()
    * 
    * Returns information about ACP controllers in bundle
    * 
    * Returns: array($item, ...)
    * 
    * $item - ACP controller, item in main navigation bar
    *    = array
    *    (
    *       string $name  - name of item (controller), displayed in main navigation bar
    *       enum   $type  - type of item
    *       string $title - (optional, leave null for no title) title displayed when link is hovered
    *       string $page  - base page of controller, e.g. 'comments'
    *    )
    * 
    * enum $type - leave null, types will be later
    */
   
   public function info();
}