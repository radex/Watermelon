<?php if(!defined('WTRMLN_IS')) exit;
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
?>
<h1>Blog</h1>
<?php if($mini) $i = 0; ?>
<list object $newsList>
<a name="news_<$id>"></a>
<h2><$title></h2>
<div class="date">napisany <date $date> przez <nick $author></div>
<?=nl2br($text)?>
<?php if($mini) $i++; ?>
<?php if($i == 2) break; ?>
</list>