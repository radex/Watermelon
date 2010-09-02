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

class Cache
{
   public static function fetch($type, $id); // fetch? get? retrieve? | type? engine? driver? | id? input?
   public static function set($type, $id, $content); // content? value? output?
   public static function delete($type, $id);
   public static function clear($type, $type, ...);
   public static function clearAll();
   
   public static function registerDriver($type, $driverObject); // pass by object? by name (and static methods there)?
}

// cache drivers as delegates (yep, Cocoa habit)
// or maybe not...

interface CacheDriver
{
   public function cache_fetch($id); // only id? or perhaps type too (multiple drivers in same class)?
   public function cache_set($id, $content);
   public function cache_delete($id);
   public function cache_clear();
   
   public function cache_filterID($id); // changing ID (which might contain non-ASCII characters) to something more certain (like hash) - it's the point, where inheritance (instead of implementing interface) would be better
   public function cache_expires();     // how long to retain cache item before auto-deletion [it could also be variable]
}