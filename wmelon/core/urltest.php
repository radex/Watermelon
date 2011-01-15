<?php
 //  
 //  This file is part of Watermelon CMS
 //  
 //  Copyright 2011 Radosław Pietruszewski.
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
 * Watermelon URL test file
 * 
 * Used to test whether mod_rewrite works, by requesting this file (and passing URL to redirect back to). Rewrite directive appends '&works' to query string, which allows to determine whether it works correctly.
 */

$backTo = base64_decode($_GET['backto']);
$works = isset($_GET['works']);

if($works)
{
   $backTo .= '&works';
}

header('Location:' . $backTo);