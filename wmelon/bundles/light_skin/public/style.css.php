<?php
 //  
 //  This file is part of Watermelon
 //  
 //  Copyright 2011 Radosław Pietruszewski.
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

//
// TODO: make some kind of universal Watermelon's css/js merging thing
// 

header('Content-Type: text/css');


include '../../watermelon/public/basic.css'; echo "\n\n\n/*---*/\n\n\n";

$wmcss = file_get_contents('../../watermelon/public/watermelon.css');
$wmcss = str_replace('url(', 'url(../../watermelon/public/', $wmcss);
echo $wmcss . "\n\n\n/*---*/\n\n\n";

include 'style.css';