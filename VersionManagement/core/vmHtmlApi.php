<?php
require_once ( __DIR__ . DIRECTORY_SEPARATOR . 'vmApi.php' );

/**
 * provides methods for html output
 */
class vmHtmlApi
{
   /**
    * Prints a table row in the plugin config area
    */
   public static function htmlConfigTableRow ()
   {
      echo '<tr>';
   }

   /**
    * triggers whiteboard menu if installed
    */
   public static function htmlPluginTriggerWhiteboardMenu ()
   {
      if ( plugin_is_installed ( 'WhiteboardMenu' ) &&
         file_exists ( config_get_global ( 'plugin_path' ) . 'WhiteboardMenu' )
      )
      {
         require_once ( __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR .
            'WhiteboardMenu' . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'wmApi.php' );
         echo '<link rel="stylesheet" href="plugins/WhiteboardMenu/files/whiteboardmenu.css"/>';
         wmApi::printWhiteboardMenu ();
      }
   }

   /**
    * prints initial ressources for the page
    */
   public static function htmlInitializeRessources ()
   {
      echo '<script type="text/javascript" src="plugins/VersionManagement/files/version_management.js"></script>';
      echo '<link rel="stylesheet" href="plugins/VersionManagement/files/version_management.css"/>';
   }

   /**
    * Prints a category column in the plugin config area
    *
    * @param $colspan
    * @param $rowspan
    * @param $langString
    */
   public static function htmlConfigCategoryColumn ( $colspan, $rowspan, $langString )
   {
      echo '<td class="category" colspan="' . $colspan . '" rowspan="' . $rowspan . '">' . plugin_lang_get ( $langString ) . '</td>';
   }

   /**
    * Prints a title row in the plugin config area
    *
    * @param $colspan
    * @param $langString
    */
   public static function htmlConfigTableTitleRow ( $colspan, $langString )
   {
      echo '<tr><td class="form-title" colspan="' . $colspan . '">' . plugin_lang_get ( $langString ) . '</td></tr>';
   }

   /**
    * Prints a radio button element in the plugin config area
    *
    * @param $colspan
    * @param $name
    */
   public static function htmlConfigRadioButton ( $colspan, $name )
   {
      echo '<td width="100px" colspan="' . $colspan . '">';
      echo '<label>';
      echo '<input type="radio" name="' . $name . '" value="1"';
      echo ( ON == plugin_config_get ( $name ) ) ? 'checked="checked"' : '';
      echo '/>' . lang_get ( 'yes' );
      echo '</label>';
      echo '<label>';
      echo '<input type="radio" name="' . $name . '" value="0"';
      echo ( OFF == plugin_config_get ( $name ) ) ? 'checked="checked"' : '';
      echo '/>' . lang_get ( 'no' );
      echo '</label>';
      echo '</td>';
   }

   /**
    * Prints a color picker element in the plugin config area
    *
    * @param $colspan
    * @param $name
    * @param $default
    */
   public static function htmlConfigColorPicker ( $colspan, $name, $default )
   {
      echo '<td width="100px" colspan="' . $colspan . '">';
      echo '<label>';
      echo '<input class="color {pickerFace:4,pickerClosable:true}" type="text" name="' . $name . '" value="' .
         plugin_config_get ( $name, $default ) . '" />';
      echo '</label>';
      echo '</td>';
   }

   /**
    * prints version name column
    *
    * @param vmVersion $version
    */
   public static function htmlVersionViewNameColumn ( vmVersion $version )
   {
      $getEdit = 0;
      if ( isset( $_GET[ "edit" ] ) )
      {
         $getEdit = $_GET[ "edit" ];
      }

      echo '<td>';
      echo '<input type="hidden" name="version_id[]" value="' . $version->getVersionId () . '"/>';
      if ( $getEdit == 1 )
      {
         echo '<label>';
         echo '<span class="input" style="width:100%;">';
         echo '<input type="text" name="version_name[]" 
            style="width:100%;" maxlength="64" value="' .
            string_attribute ( $version->getVersionName () ) . '" />';
         echo '</span>';
         echo '</label>';
      }
      else
      {
         if ( helper_get_current_project () != $version->getProjectId () )
         {
            echo '[' . project_get_name ( $version->getProjectId () ) . ']&nbsp;' . $version->getVersionName ();
         }
         else
         {
            echo $version->getVersionName ();
         }
      }
      echo '</td>';
   }

   /**
    * prints version released status column
    *
    * @param vmVersion $version
    */
   public static function htmlVersionViewReleasedColumn ( vmVersion $version )
   {
      $getEdit = 0;
      if ( isset( $_GET[ "edit" ] ) )
      {
         $getEdit = $_GET[ "edit" ];
      }

      echo '<td>';
      if ( $getEdit )
      {
         echo '<span class="checkbox">';
         echo '<input type="checkbox" name="version_released[]" value="' .
            $version->getVersionId () . '"';
         check_checked ( (bool)$version->getReleased (), TRUE );
         echo '/>';
         echo '</span>';
      }
      else
      {
         echo trans_bool ( $version->getReleased () );
      }
      echo '</td>';
   }

   /**
    * prints version obsolete status column
    *
    * @param vmVersion $version
    */
   public static function htmlVersionViewObsoleteColumn ( vmVersion $version )
   {
      $getEdit = 0;
      if ( isset( $_GET[ "edit" ] ) )
      {
         $getEdit = $_GET[ "edit" ];
      }

      echo '<td>';
      if ( $getEdit )
      {
         echo '<span class="checkbox">';
         echo '<input type="checkbox" name="version_obsolete[]" value="' .
            $version->getVersionId () . '"';
         check_checked ( (bool)$version->getObsolete (), TRUE );
         echo '/>';
         echo '</span>';
      }
      else
      {
         echo trans_bool ( $version->getObsolete () );
      }
      echo '</td>';
   }

   /**
    * prints version date order column
    *
    * @param vmVersion $version
    */
   public static function htmlVersionViewDateOrderColumn ( vmVersion $version )
   {
      $getEdit = 0;
      if ( isset( $_GET[ "edit" ] ) )
      {
         $getEdit = $_GET[ "edit" ];
      }

      echo '<td>';
      if ( $getEdit )
      {
         echo '<label>';
         echo '<span class="input">';
         echo '<input type="text" name="version_date_order[]"
         class="datetime" size="15" value="' .
            ( date_is_null ( $version->getDateOrder () ) ?
               '' : string_attribute ( date ( config_get ( 'calendar_date_format' ), $version->getDateOrder () ) ) ) . '" />';
         echo '</span>';
         echo '</label>';
      }
      else
      {
         echo date_is_null ( $version->getDateOrder () ) ?
            '' : string_attribute ( date ( config_get ( 'calendar_date_format' ), $version->getDateOrder () ) );
      }
      echo '</td>';
   }

   /**
    * prints version description column
    *
    * @param vmVersion $version
    */
   public static function htmlVersionViewDescriptionColumn ( vmVersion $version )
   {
      $getEdit = 0;
      if ( isset( $_GET[ "edit" ] ) )
      {
         $getEdit = $_GET[ "edit" ];
      }

      echo '<td width="100">';
      if ( $getEdit )
      {
         echo '<span class="text">';
         echo '<input type="text" name="version_description[]" value="' .
            string_attribute ( $version->getDescription () ) . '"/>';
         echo '</span>';
      }
      else
      {
         echo string_display ( $version->getDescription () );
      }
      echo '</td>';
   }

   /**
    * prints document type column when document management plugin is installed
    *
    * @param vmVersion $version
    */
   public static function htmlVersionViewDocumentTypeColumn ( vmVersion $version )
   {
      if ( vmApi::checkDMPluginIsInstalled () )
      {
         require_once ( __DIR__ . '/../../DocumentManagement/core/specmanagement_database_api.php' );
      }
      elseif ( vmApi::checkSMPluginIsInstalled () )
      {
         require_once ( __DIR__ . '/../../SpecManagement/core/specmanagement_database_api.php' );
      }
      $dManagementDbApi = new specmanagement_database_api();

      $getEdit = 0;
      if ( isset( $_GET[ "edit" ] ) )
      {
         $getEdit = $_GET[ "edit" ];
      }

      $typeId = $dManagementDbApi->get_type_by_version ( $version->getVersionId () );
      $type = $dManagementDbApi->get_type_string ( $typeId );

      echo '<td>';
      if ( $getEdit == 1 )
      {
         echo '<span class="select"><select ' . helper_get_tab_index () . ' id="proj-version-type" name="version-type[]">';
         echo '<option value=""></option>';
         $rypeRows = $dManagementDbApi->get_full_types ();
         foreach ( $rypeRows as $rypeRow )
         {
            $availableType = $rypeRow[ 1 ];
            echo '<option value="' . $availableType . '"';
            check_selected ( string_attribute ( $type ), $availableType );
            echo '>' . $availableType . '</option>';
         }
         echo '</select></span>';
      }
      else
      {
         echo string_display ( $type );
      }
      echo '</td>';
   }

   /**
    * prints action column
    *
    * @param vmVersion $version
    */
   public static function htmlVersionViewActionColumn ( vmVersion $version )
   {
      $getEdit = 0;
      if ( isset( $_GET[ "edit" ] ) )
      {
         $getEdit = $_GET[ "edit" ];
      }

      if ( $getEdit == 0 )
      {
         echo '<td>';
         echo '<a style="text-decoration: none;" href="' . plugin_page ( 'version_view_delete' ) .
            '&amp;version_id=' . $version->getVersionId () . '">';
         echo '<span class="input">';
         echo '<input type="button" value="' . lang_get ( 'delete_link' ) . '" />';
         echo '</span>';
         echo '</a>';
         echo '</td>';
      }
   }

   /**
    * prints the opening tags for a version row
    *
    * @param vmVersion $version
    */
   public static function htmlVersionViewRowOpen ( vmVersion $version )
   {
      if ( !$version->isVersionIsUsed () )
      {
         echo '<tr style="background-color: ' . plugin_config_get ( 'unused_version_row_color' ) . '">';
      }
      else
      {
         echo '<tr>';
      }
   }

   /**
    * prints the foot table of the version view page
    */
   public static function htmlVersionViewFootTable ()
   {
      $getEdit = 0;
      if ( isset( $_GET[ "edit" ] ) )
      {
         $getEdit = $_GET[ "edit" ];
      }

      $getSort = NULL;
      if ( isset( $_GET[ 'sort' ] ) )
      {
         $getSort = $_GET[ 'sort' ];
      }

      $getObsolete = NULL;
      if ( isset( $_GET[ 'obsolete' ] ) )
      {
         $getObsolete = $_GET[ 'obsolete' ];
      }

      echo '<table class="width100 down">';
      echo '<tbody>';
      echo '<tr class="footer">';
      if ( $getEdit == 1 )
      {
         $currentProjectId = helper_get_current_project ();
         $versions = version_get_all_rows_with_subs ( $currentProjectId, NULL, vmApi::getObsoleteValue () );
         $initialRowCount = ( count ( $versions ) );
         $dminstalled = vmApi::checkDMManagementPluginIsInstalled ();

         echo '<td class="left">';
         echo '<input type="button" value="+" onclick="add_version_row(' . $dminstalled . ')" />&nbsp;';
         echo '<input type="button" value="-" onclick="del_version_row(' . $initialRowCount . ')" />&nbsp;';
         echo '</td>';
      }

      echo '<td colspan="5" class="center">';
      if ( $getEdit == 1 )
      {
         echo '<a style="text-decoration: none;" href="' . plugin_page ( 'version_view_update' ) .
            '&amp;sort=' . $getSort . '&amp;edit=1&amp;obsolete=' . $getObsolete . '">';
         echo '<span class="input">';
         echo '<input type="hidden" name="sort" value="' . $getSort . '" />';
         echo '<input type="submit" value="' . plugin_lang_get ( 'version_view_table_foot_edit_done' ) . '" />';
         echo '</span>';
         echo '</a>';
      }
      else
      {
         echo '<a style="text-decoration: none;" href="' . plugin_page ( 'version_view_page' ) .
            '&amp;sort=' . $getSort . '&amp;edit=1&amp;obsolete=' . $getObsolete . '">';
         echo '<span class="input">';
         echo '<input type="submit" value="' . plugin_lang_get ( 'version_view_table_foot_edit' ) . '" />';
         echo '</span>';
         echo '</a>';
      }
      echo '</td>';
      echo '</tr>';
      echo '</tbody>';
      echo '</table>';
   }

   /**
    * prints the opening tags for the main table in version view page
    */
   public static function htmlVersionViewMainTableOpen ()
   {
      $getEdit = 0;
      if ( isset( $_GET[ "edit" ] ) )
      {
         $getEdit = $_GET[ "edit" ];
      }

      if ( $getEdit == 1 )
      {
         echo '<form action="' . plugin_page ( 'version_view_update' ) . '" method="post">';
      }
      echo '<table id="version_view" class="width100 down">';
   }

   /**
    * prints the head table in the version view page
    */
   public static function htmlVersionViewHeadTable ()
   {
      $getEdit = 0;
      if ( isset( $_GET[ "edit" ] ) )
      {
         $getEdit = $_GET[ "edit" ];
      }

      $getSort = NULL;
      if ( isset( $_GET[ 'sort' ] ) )
      {
         $getSort = $_GET[ 'sort' ];
      }

      $getObsolete = NULL;
      if ( isset( $_GET[ 'obsolete' ] ) )
      {
         $getObsolete = $_GET[ 'obsolete' ];
      }

      echo '<table class="width100">';
      echo '<tr>';
      echo '<td class="form-title">';
      echo plugin_lang_get ( 'version_view_title' );
      echo '</td>';
      echo '<td class="form-title right">';
      if ( $getObsolete == 1 )
      {
         echo '<a style="text-decoration: none;" href="' . plugin_page ( 'version_view_page' ) .
            '&amp;sort=' . $getSort . '&amp;edit=' . $getEdit . '&amp;obsolete=0">';
         echo '<span class="input">';
         echo '<input type="submit" value="' . plugin_lang_get ( 'version_view_table_head_hide_obsolete' ) . '" />';
      }
      else
      {
         echo '<a style="text-decoration: none;" href="' . plugin_page ( 'version_view_page' ) .
            '&amp;sort=' . $getSort . '&amp;edit=' . $getEdit . '&amp;obsolete=1">';
         echo '<span class="input">';
         echo '<input type="submit" value="' . plugin_lang_get ( 'version_view_table_head_show_obsolete' ) . '" />';
      }
      echo '</span>';
      echo '</a>';
      echo '</td>';
      echo '</tr>';
      echo '</table>';
   }

   /**
    * prints the head area of the main table
    */
   public static function htmlVersionViewMainTableHead ()
   {
      $getEdit = 0;
      if ( isset( $_GET[ "edit" ] ) )
      {
         $getEdit = $_GET[ "edit" ];
      }

      $getObsolete = NULL;
      if ( isset( $_GET[ 'obsolete' ] ) )
      {
         $getObsolete = $_GET[ 'obsolete' ];
      }

      echo '<thead>';
      echo '<tr class="row-category2">';
      echo '<th>' . plugin_lang_get ( 'version_view_table_head_version' ) .
         # sort asc by version
         '<a href="' . plugin_page ( 'version_view_page' ) .
         '&amp;sort=vasc&amp;edit=' . $getEdit . '&amp;obsolete=' . $getObsolete . '">&nbsp;' .
         '<img class="symbol" src="plugins/VersionManagement/files/sort_az_asc.png"></a>' .
         # sort desc by version
         '<a href="' . plugin_page ( 'version_view_page' ) .
         '&amp;sort=vdesc&amp;edit=' . $getEdit . '&amp;obsolete=' . $getObsolete . '">&nbsp;' .
         '<img class="symbol" src="plugins/VersionManagement/files/sort_az_des.png"></a>' .
         '</th>';
      echo '<th>' . plugin_lang_get ( 'version_view_table_head_released' ) . '</th>';
      echo '<th>' . plugin_lang_get ( 'version_view_table_head_obsolete' ) . '</th>';
      echo '<th>' . plugin_lang_get ( 'version_view_table_head_date' ) .
         # sort asc by date
         '<a href="' . plugin_page ( 'version_view_page' ) .
         '&amp;sort=dasc&amp;edit=' . $getEdit . '&amp;obsolete=' . $getObsolete . '">&nbsp;' .
         '<img class="symbol" src="plugins/VersionManagement/files/sort_az_asc.png"></a>' .
         # sort desc by date
         '<a href="' . plugin_page ( 'version_view_page' ) .
         '&amp;sort=ddesc&amp;edit=' . $getEdit . '&amp;obsolete=' . $getObsolete . '">&nbsp;' .
         '<img class="symbol" src="plugins/VersionManagement/files/sort_az_des.png"></a>' .
         '</th>';
      echo '<th>' . plugin_lang_get ( 'version_view_table_head_description' ) . '</th>';
      if ( vmApi::checkDMManagementPluginIsInstalled () )
      {
         echo '<th>' . plugin_lang_get ( 'version_view_table_head_type' ) . '</th>';
      }
      if ( $getEdit == 0 )
      {
         echo '<th>' . plugin_lang_get ( 'version_view_table_head_action' ) . '</th>';
      }
      echo '</tr>';
      echo '</thead>';
   }
}