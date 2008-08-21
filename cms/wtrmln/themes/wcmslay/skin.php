<?php if(!defined('WTRMLN_IS')) die;

/* meta (poładniamy source)
**************************/

$meta = '';

foreach($_w_metaSrc as $metaItem)
{
	$meta .= "   " . $metaItem . "\n";
}

/* menu
***************************/

if(!defined('NOMENU'))
{
	$menus = DB::query("SELECT * FROM `menu`");
   
	$menu = '';
	
	while($menu_item = $menus->to_obj())
	{
		$menu .= '<div class="h1">' . $menu_item->capt . '</div>' . $menu_item->content;
	}
   
   //przetwarzanie
   
   $menu = str_replace('<?=', '<?php echo ', $menu);
   
   ob_start();
   $menu = eval('?>' . $menu . '<?php ');
   $menu = ob_get_contents();
   @ob_end_clean();
}

/* takie tam...
*********************************/

$siteheader = WTRMLN_SITENAME;
$siteslogan = WTRMLN_SITESLOGAN;

include WTRMLN_THEMEPATH . 'index.php';
