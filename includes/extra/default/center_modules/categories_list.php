<?php
    /* MODUL: Kategorien auf der Startseite anzeigen (c_list Modul)
     * 2020 by Gulliver72 - transferred to Github and entered in MMLC
     * 2016 Anpassung an modified 2.x und PHP7: |Alex|awids
     * 2011 Aktualisierung: koshiro aka Micha und mrsmartmouse
     * 2009 Anpassung an modified 1.x: sgei
     * Quelle: xtc-load.de - Autor: unbekannt
     */
   
     require_once(DIR_WS_MODULES . FILENAME_CATEGORIES_LIST);
	
     $default_smarty->assign('CATEGORIES_LIST', $module_categories);
