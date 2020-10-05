<?php
	// MODUL: Kategorien auf der Startseite anzeigen (c_list Modul)
	// Anpassung an modified 1.x: sgei
	// Anpassung an modified 2.x: |Alex|
	// Quelle: xtc-load.de - Autor: unbekannt
	
	require_once(DIR_WS_MODULES . FILENAME_CATEGORIES_LIST);
	
	$default_smarty->assign('CATEGORIES_LIST', $module_categories);
