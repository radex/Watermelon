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
 * class GenericCache
 * 
 * Common cache for storing all kinds of data
 * 
 * Use it for storing small amounts of data when you don't need specific format
 * 
 * If you need more data to store - inherit from this class and override directory() method, or (when you need more specific format or more efficient storing manner) inherit from FileCache, or even directly from Cache
 */

class GenericCache extends FileCache
{
   protected function directory()
   {
      
   }
}