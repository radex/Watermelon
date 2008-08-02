<?php if(!defined(WTRMLN_IS)) die;

/* meta (poładniamy source)
**************************/

foreach($_w_metaSrc as $metaItem)
{
	$meta .= "\t" . $metaItem . "\n";	
}

/* menu
***************************/

$db = DB::Instance();

if(!defined('NOMENU'))
{
	$menus = $db->query("SELECT * FROM `menu`");
	
	while($menu_e = $menus->to_obj())
	{
		$menu .= '<div class="h1">' . $menu_e->menu_capt . '</div>' . $menu_e->menu_content . '<hr>';
	}
	
	$menu = substr($menu, 0, -4);
	
	//przetwarzanie
	
	if (@ini_get('short_open_tag') === FALSE)
	{
	//jeśli nie można używać <?
		$menu = str_replace('<?=', '<?php echo ', $menu);
		$menu = str_replace('<?',  '<?php', $menu);
	}
	
	ob_start();
	$menu = eval('?>' . $menu . '<?php ');
	$menu = ob_get_contents();
	@ob_end_clean();
	
}

/* takie tam...
*********************************/

$zapytan = (int)$db->queries();
$zapytan = $zapytan . ' ' . generatePlFormOf($zapytan, 'zapytanie', 'zapytania', 'zapytań');

$siteheader = WTRMLN_SITENAME;

include WTRMLN_THEMEPATH . 'index.php';
