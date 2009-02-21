<?php
/********************************************************************

  Watermelon CMS

Copyright 2009 Radosław Pietruszewski

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
version 2 as published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.

********************************************************************/

/*
 * string bbcode(string $text)
 * 
 * parsuje bbcode (zamienia na html)
 * 
 * string $text - bbcode do sparsowania
 */

function bbcode($text)
{
   include_once 'handycode.class.php';
   
   $obj = new handyCode();
   
   return $obj->parse($text, null, 0, 1);
}

/*
 * string bbcode(string $text)
 * 
 * parsuje bbcode (zamienia na html)
 * z włączoną obsługą cache'owania
 * 
 * string $text - bbcode do sparsowania
 */

function bbcode_cached($text)
{
   if(!defined('CACHE_BBCODE')) return bbcode($text);
   
   $cached = Cache::GetBBCode($text);
   
   if($cached) return $cached;
   
   include_once 'handycode.class.php';
   
   $obj = new handyCode();
   
   $parsed = $obj->parse($text, null, 0, 1);
   
   Cache::CacheBBCode($text, $parsed);
   
   return $parsed;
}

?>