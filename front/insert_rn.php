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
include ("../../../inc/includes.php");
include ("../../../inc/config.php");

Session::checkLoginUser();
Session::checkRight("profile", READ);

$ent_id =	$_POST["id"];

if(isset($_POST["rn"])) {
	$rn = 	$_POST["rn"]; 
	
	$query = "SELECT name FROM glpi_entities WHERE id = ".$ent_id;
	$result = $DB->query($query) or die ("error insert");
	
	$location = $DB->result($result,0,'name');

	$insert = "
		INSERT INTO glpi_plugin_tr_rn (entities_id, rn) 
		VALUES ('$ent_id', '$rn') 
		ON DUPLICATE KEY UPDATE rn='$rn'";

    $DB->query($insert) or die ("Error inserting rn");
	
	echo "<meta HTTP-EQUIV='refresh' CONTENT='0;URL=".$CFG_GLPI['root_doc']."/front/entity.form.php?id=".$ent_id."'>";
}

if($_POST["rn"] == "") {
	
	$query = "DELETE FROM glpi_plugin_tr_rn WHERE entities_id = ".$_POST["id"];
	$DB->query($query) or die ("error removing rn");
	
	echo "<meta HTTP-EQUIV='refresh' CONTENT='0;URL=".$CFG_GLPI['root_doc']."/front/entity.form.php?id=".$ent_id."'>";	
}