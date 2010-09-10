<?php
 //  
 //  This file is part of Watermelon CMS
 //  
 //  Copyright 2009-2010 Radosław Pietruszewski.
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

// general libraries

include 'DB/DB.php';
include 'Registry/Registry.php';
include 'Loader.php';
include 'EventCenter.php';
include 'Cache/Cache.php';
include 'Translations/Translations.php';

// testing&development stuff

if(defined('WM_Debug'))
{
   include 'testing/UnitTester.php';
}

include 'testing/Exception.php';
include 'testing/Benchmark.php';

// module types headers

include 'headers/controller.php';
include 'headers/model.php';
include 'headers/extension.php';
include 'headers/blockset.php';
include 'headers/skin.php';
include 'headers/view.php';