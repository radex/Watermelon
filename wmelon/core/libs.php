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

include WM_Core . 'helpers/helpers.php';

include WM_Core . 'DB/DB.php';
include WM_Core . 'Registry/Registry.php';
include WM_Core . 'Loader.php';
include WM_Core . 'EventCenter.php';
// include 'Cache/Cache.php';
// include 'Translations/Translations.php';
include WM_Core . 'PHPTAL/PHPTAL.php';

// testing&development stuff

if(defined('WM_Debug'))
{
   include WM_Core . 'testing/UnitTester.php';
}

include WM_Core . 'testing/Exception.php';
include WM_Core . 'testing/Benchmark.php';

// module types headers

include WM_Core . 'headers/ACPInfo.php';
include WM_Core . 'headers/Blockset.php';
include WM_Core . 'headers/Controller.php';
include WM_Core . 'headers/Extension.php';
include WM_Core . 'headers/Model.php';
include WM_Core . 'headers/Skin.php';
include WM_Core . 'headers/View.php';