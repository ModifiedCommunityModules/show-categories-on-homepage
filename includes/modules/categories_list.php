<?php
    /* MODUL: Kategorien auf der Startseite anzeigen (c_list Modul)
     * 2020 by Gulliver72 - transferred to Github and entered in MMLC
     * 2016 Anpassung an modified 2.x und PHP7: |Alex|awids
     * 2011 Aktualisierung: koshiro aka Micha und mrsmartmouse
     * 2009 Anpassung an modified 1.x: sgei
     * Quelle: xtc-load.de - Autor: unbekannt
     */
   
    $module_smarty = new smarty;
    
    $module_content = array ();

    $module_smarty->assign('language', $_SESSION['language']);

    // set cache ID
    if (!CacheCheck())
    {
        $module_smarty->caching = 0;
        $module_categories = $module_smarty->fetch(CURRENT_TEMPLATE . '/module/categories_list.html');
    } else {
        $module_smarty->caching = 1;
        $module_smarty->cache_lifetime = CACHE_LIFETIME;
        $module_smarty->cache_modified_check = CACHE_CHECK;
        $cache_id = md5($_SESSION['language'] . $_SESSION['customers_status']['customers_status_id'] . $cPath);

        if (!$module_smarty->is_cached(CURRENT_TEMPLATE.'/module/categories_list.html', $cache_id) || !$cache)
        {

            $module_smarty->assign('tpl_path', 'templates/' . CURRENT_TEMPLATE . '/');

            $categories_string = '';
        
            $group_check = '';
        
            if (defined('GROUP_CHECK') && GROUP_CHECK == 'true')
            {
                $group_check = "c.group_permission_" . $_SESSION['customers_status']['customers_status_id'] . "=1 AND";
            }
        
            $categories_query = "SELECT c.categories_id,
                                        c.categories_image,
                                        cd.categories_name,
                                        cd.categories_description
                                FROM " . TABLE_CATEGORIES . " AS c,
                                     " . TABLE_CATEGORIES_DESCRIPTION . " AS cd
                                WHERE c.categories_id = cd.categories_id
                                AND c.parent_id = '0'
                                AND " . $group_check . " 
                                    c.categories_status = '1'
                                AND cd.language_id = '" . (int)$_SESSION['languages_id'] . "'
                                ORDER BY c.sort_order ASC";

            $categories_query = xtDBquery($categories_query);

            while($categories = xtc_db_fetch_array($categories_query, true))
            {
                $category_link = xtc_category_link($categories['categories_id'], $categories['categories_name']);

                $image = '';
            
                if ($categories['categories_image'] != '')
                {
                    $image = DIR_WS_IMAGES . 'categories/' . $categories['categories_image'];
                
                    if (!file_exists(DIR_FS_CATALOG . $image))
                    {
                        if (defined('CATEGORIES_IMAGE_SHOW_NO_IMAGE') && CATEGORIES_IMAGE_SHOW_NO_IMAGE == 'true')
                        {
                            $image = DIR_WS_IMAGES . 'categories/noimage.gif';
                        }
                    }
                } else {                     
                    if (defined('CATEGORIES_IMAGE_SHOW_NO_IMAGE') && CATEGORIES_IMAGE_SHOW_NO_IMAGE == 'true')
                    {
                        $image = DIR_WS_IMAGES . 'categories/noimage.gif';
                    }
                }

    
                $module_content[] = array ('CATEGORY_NAME'        => $categories['categories_name'],
                                           'CATEGORY_IMAGE_TRUE'  => $categories['categories_image'],        
                                           'CATEGORY_IMAGE'       => (($image != '') ? DIR_WS_BASE . $image : ''), 
                                           'CATEGORY_LINK'        => xtc_href_link(FILENAME_DEFAULT,
                                                                     xtc_get_all_get_params(array(array('cat', 'page', 'filter_id', 'manufacturers_id'))) . 
                                                                     $category_link), 
                                           'CATEGORY_DESCRIPTION' => $categories['categories_description']
                                           );

            }
        }
  
        $module_smarty->assign('module_content', $module_content);

        $module_categories = $module_smarty->fetch(CURRENT_TEMPLATE . '/module/categories_list.html', $cache_id);
    }
