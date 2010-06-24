<?php if(!defined('WM_IS')) die;
//TODO: zrobić porządek
/*
global $timer, $microtime2;
$_st = $timer;
$_et = $microtime2;
var_dump($_st, $_et);

$microtime = explode(' ', $_et);
$msec = substr($microtime[0],2);
$sec  = $microtime[1];
$time = $sec . $msec;
$_et = substr($time, 0, -2);

Benchmark::$benchmarks['libload8'] = $_st;
Benchmark::end('libload8', $_et);
*/
/* menu
***************************/
if(!defined('NOMENU'))
{
	$menus = DB::query("SELECT * FROM `__menu`");
   
	$menulist = array();
	
	while($menu_item = $menus->to_obj())
	{
	   if(!empty($menu_item->condition))
		{
		   $condition = $menu_item->condition;
		   $toEval = '
		   if(' . $condition . ')
		   {
		   $menulist[$menu_item->position] = \'<div class="h1">\' . $menu_item->capt . \'</div>\' . $menu_item->content;
		   }
		   ';
		   
		   eval($toEval);
		}
		else
		{
		   $menulist[$menu_item->position] = '<div class="h1">' . $menu_item->capt . '</div>' . $menu_item->content;
		}
	}
	
	$menu = '';
	
	for($i = 0, $j = count($menulist); $i < $j; $i++)
	{
	   if(!isset($menulist[$i]))
	   {
	      $j++;
	      continue;
	   }
	   $menu .= $menulist[$i];
	   unset($menulist[$i]);
	}
   
   //przetwarzanie
   
   $menu = str_replace('<?=', '<?php echo ', $menu);
   
   ob_start();
   $menu = ViewTags::Process($menu);
   $menu = eval('?>' . $menu . '<?php ');
   $menu = ob_get_contents();
   @ob_end_clean();
}

/* topmenu
*********************************/
/*
<li class="actual_page"><a href="#aad">Główna</a></li>
            <li><a href="#bd">Inna</a></li>
            <li><a href="#cd">Jeszcze inna</a></li>*/

$topmenus = Config::getConf('top_menus');
$topmenus = unserialize($topmenus);

foreach($topmenus as $var)
{
   $condition = 'if(' . $var[2] . '){$s=true;}else{$s=false;}';
   eval($condition);
   
   $menusStr .= '<li' . ($s ? ' class="actual_page"' : '') . '><a href="' . site_url($var[1]) . '">' . $var[0] . '</a></li>';
}
/* takie tam...
*********************************/

$siteheader = WM_SITENAME;
$siteslogan = WM_SITESLOGAN;

include WM_THEMEPATH . 'index.php';
