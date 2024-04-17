<?php
/*
   ------------------------------------------------------------------------
   Plugin TR
   Copyright (C) 2024 by Gabriel Carneiro
   https://github.com/bielcode/tr
   Forked from https://github.com/juniormarcati/os
   ------------------------------------------------------------------------
   LICENSE
   This file is part of Plugin TR project.
   Plugin TR is free software: you can redistribute it and/or modify
   it under the terms of the GNU Affero General Public License as published by
   the Free Software Foundation, either version 3 of the License, or
   (at your option) any later version.
   Plugin TR is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
   GNU Affero General Public License for more details.
   You should have received a copy of the GNU Affero General Public License
   along with Plugin TR. If not, see <http://www.gnu.org/licenses/>.
   ------------------------------------------------------------------------
   @package   Plugin TR
   @author    Gabriel Carneiro
   @co-author
   @copyright Copyright (c) 2024 TR Plugin Development team
   @license   AGPL License 3.0 or (at your option) any later version
              http://www.gnu.org/licenses/agpl-3.0-standalone.html
   @link      https://github.com/bielcode/tr
   @since     2024
   ------------------------------------------------------------------------
 */
class PluginTrConfig extends CommonDBTM {
   static protected $notable = true;
   static function getMenuName() {
      return __('Tr');
   }
   static function getMenuContent() {
   	global $CFG_GLPI;
   	$menu = array();
      $menu['title']   = __('Termo de Responsabilidade','tr');
      $menu['page']    = "/plugins/tr/front/index.php";
   	return $menu;
   }
   // add tabs
   function getTabNameForItem(CommonGLPI $item, $withtemplate = 0) {
      // add ticket tab
      switch (get_class($item)) {
         case 'Ticket':
            return array(1 => __('Termo de Responsabilidade','tr'));
         default:
      }
      // add entity tab
      switch (get_class($item)) {
         case 'Entity':
            return array(1 => __('Dados para T.R.','tr'));
         default:
         return '';
      }
   }
   static function displayTabContentForItem(CommonGLPI $item, $tabnum = 1, $withtemplate = 0) {
      switch (get_class($item)) {
         case 'Ticket':
            $config = new self();
            $config->showFormDisplay();
            break;
      }
      switch (get_class($item)) {
         case 'Entity':
            $config = new self();
            $config->showFormDisplayEntity();
            break;
      }
      return true;
   }
   // ticket tab
   function showFormDisplay() {
      global $CFG_GLPI, $DB;
      $ID = $_REQUEST['id'];
      $url = $CFG_GLPI['url_base'];
      echo "<head>";
      echo "<script type='text/javascript'>";
      echo "function setIframeSource() {";
      echo "var theSelect = document.getElementById('PageType');";
      echo "var theIframe = document.getElementById('TrIframe');";
      echo "var theUrl;";
      echo "theUrl = theSelect.options[theSelect.selectedIndex].value;";
      echo "theIframe.src = theUrl;";
      echo "}";
      echo "</script>";
      echo "</head>";
      echo "<body>";
      echo "<form id='form1' method='post'>";
      echo "<label>Selecione o Layout </label>";
      echo "<select id='PageType' onchange='setIframeSource()'>";
      echo "<option value='$url/plugins/tr/front/tr_pdf.php?id=$ID'>A4</option>";
      echo "<option value='$url/plugins/tr/front/tr_pdflabel.php?id=$ID'>Label</option>";
      echo "</select>";
      echo "</form>";
      echo "<iframe id='TrIframe' src='$url/plugins/tr/front/tr_pdf.php?id=$ID' frameborder='0' marginwidth='0' marginheight='0' width='80%' height='700'></iframe>";
      echo "</body>";
   }
   // entity tab
   function showFormDisplayEntity() {
      global $CFG_GLPI, $DB;
      // geting rn on db
      if(isset($_GET['id'])) {
	      $query_rn = "SELECT * FROM glpi_plugin_os_rn WHERE entities_id = ".$_GET['id'];
	      $result_rn = $DB->query($query_rn) or die ("erro");
			$ent_info = $DB->fetchAssoc($result_rn);
			$EmpresaRn = $ent_info['rn'];
		}
		else {
			$EmpresaRn = '';
		}
      
      $canedit = Session::haveRight(Config::$rightname, UPDATE);
      if ($canedit) {
         echo "<form name='form' action='../plugins/os/front/insert_rn.php' method='post'>";
      }
      echo "<td>". __('CNPJ: ') ."</td>"; 
      echo "<td><input type='text' minlength='1' maxlength='18' inputmode='number' name='rn' value=".$EmpresaRn."></td>";
      echo "</tr>";		           
      echo "<td colspan='4' class='center'>";
      echo "</td></tr>";
      echo "<tr></tr>";
      echo "<td><input type='hidden' id='id' name='id' value=".$_GET['id']."></td>";           
      $canedit = Session::haveRight(Config::$rightname, UPDATE);
      if ($canedit) {         
         echo "<input type='submit' name='update' class='submit' value=\"" . _sx('button', 'Save') . "\">";
      }
      Html::closeForm();
   }
}