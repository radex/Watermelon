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

include 'helpers/helpers.php';
include 'Form/Form.php';

include 'DB/DB.php';
include 'Registry/Registry.php';
include 'Loader.php';
include 'EventCenter.php';
include 'PHPTAL/PHPTAL.php';
include 'ViewPreFilter.php';

// testing&development stuff

if(defined('WM_Debug'))
{
   include 'testing/UnitTester.php';
}

include 'testing/Exception.php';
include 'testing/Benchmark.php';

// module types headers

include 'headers/ACPInfo.php';
include 'headers/Blockset.php';
include 'headers/Controller.php';
include 'headers/Extension.php';
include 'headers/Model.php';
include 'headers/Skin.php';
include 'headers/View.php';