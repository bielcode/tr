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
//plugin version
 define('PLUGIN_TR_VERSION', '0.1.0-beta');
// Minimal GLPI version
define('PLUGIN_TR_MIN_GLPI', '9.4');
// Maximum GLPI version
define('PLUGIN_TR_MAX_GLPI', '10.1.1');

function plugin_init_tr() {
  global $PLUGIN_HOOKS, $CFG_GLPI, $LANG;
  $PLUGIN_HOOKS['csrf_compliant']['tr'] = true;

  Plugin::registerClass('PluginTrConfig', ['addtabon' => ['Entity']]);
  Plugin::registerClass('PluginTrProfile', ['addtabon' => 'Profile']);
  $PLUGIN_HOOKS['change_profile']['tr'] = ['PluginOsProfile','initProfile'];

  if (Session::haveRight('plugin_tr', READ)) {
    Plugin::registerClass('PluginTrConfig', ['addtabon' => 'Ticket']);
 }

  $_SESSION["glpi_plugin_tr_profile"]['tr'] = 'w';
  if (isset($_SESSION["glpi_plugin_tr_profile"])) {
    $PLUGIN_HOOKS["menu_toadd"]['tr'] = array('plugins'  => 'PluginTrConfig');
    }
}

// Config page
if (Session::haveRight('config', UPDATE)) {
  $PLUGIN_HOOKS['config_page']['tr'] = 'front/index.php';
}
$PLUGIN_HOOKS['change_profile']['tr'] = 'plugin_change_profile_tr';

function plugin_version_tr() {
  return [
    'name'          => 'TR',
    'version'       => PLUGIN_TR_VERSION ,
    'author'        => 'Gabriel Carneiro',
    'license'       => 'AGPLv3+',
    'homepage'      => 'https://github.com/bielcode/tr',
    'requirements'  => [
      'glpi'  => [
        'min' => PLUGIN_TR_MIN_GLPI,
        'max' => PLUGIN_TR_MAX_GLPI,
      ]
    ]
  ];
}