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
$SelPlugin = "SELECT * FROM glpi_plugin_tr_config";
$ResPlugin = $DB->query($SelPlugin);
$Plugin = $DB->fetchAssoc($ResPlugin);
$EmpresaPlugin = $Plugin['name'];
$CnpjPlugin = $Plugin['cnpj'];
$EnderecoPlugin = $Plugin['address'];
$TelefonePlugin = $Plugin['phone'];
$CidadePlugin = $Plugin['city'];
$SitePlugin = $Plugin['site'];
$SelTicket = "SELECT * FROM glpi_tickets WHERE id = '".$_GET['id']."'";
$ResTicket = $DB->query($SelTicket);
$Ticket = $DB->fetchAssoc($ResTicket);
$TrId = $_GET['id'];
$TrNome = $Ticket['name'];
$SelDataTr = "SELECT date,date_format(date, '%d/%m/%Y') AS DataTr FROM glpi_tickets WHERE id = '".$_GET['id']."'";
$ResSelData = $DB->query($SelDataTr);
$ResSelDataFinal = $DB->fetchAssoc($ResSelData);
$DataTr = $ResSelDataFinal['DataTr'];
$SelDataInicial = "SELECT date,date_format(date, '%d/%m/%Y %H:%i') AS DataInicio FROM glpi_tickets WHERE id = '".$_GET['id']."'";
$ResDataInicial = $DB->query($SelDataInicial);
$DataInicial = $DB->fetchAssoc($ResDataInicial);
$TrData = $DataInicial['DataInicio'];
$TrDescricao = $Ticket['content'];
$SelDataFinal = "SELECT time_to_resolve,date_format(solvedate, '%d/%m/%Y %H:%i') AS DataFim FROM glpi_tickets WHERE id = '".$_GET['id']."'";
$ResDataFinal = $DB->query($SelDataFinal);
$DataFinal = $DB->fetchAssoc($ResDataFinal);
$TrDataEntrega = $DataFinal['DataFim'];
$SelSolucaoTicket = "SELECT * FROM glpi_itilsolutions WHERE items_id = '".$_GET['id']."' AND (status = '2' OR status = '3')";
$ResSolucaoTicket = $DB->query($SelSolucaoTicket);
$SolucaoTicket = $DB->fetchAssoc($ResSolucaoTicket);
$TrSolucao = is_null($SolucaoTicket)  ? 0 : $SolucaoTicket['content'];
$SelTicketUsers = "SELECT * FROM glpi_tickets_users WHERE tickets_id = '".$TrId."'";
$ResTicketUsers = $DB->query($SelTicketUsers);
$TicketUsers = $DB->fetchAssoc($ResTicketUsers);
$TrUserId = $TicketUsers['users_id'];
$SelIdTrResponsavel = "SELECT users_id FROM glpi_tickets_users WHERE tickets_id = '".$TrId."' AND type = 2";
$ResIdTrResponsavel = $DB->query($SelIdTrResponsavel);
$TrResponsavel = "";
while ($IdTrResponsavel = $DB->fetchAssoc($ResIdTrResponsavel)) {
	$SelTrResponsavelName = "SELECT * FROM glpi_users WHERE id = '".$IdTrResponsavel['users_id']."'";
	$ResTrResponsavelName = $DB->query($SelTrResponsavelName);
	$TrResponsavelFull = $DB->fetchAssoc($ResTrResponsavelName);
	$TrResponsavel .= $TrResponsavelFull['firstname']. " " .$TrResponsavelFull['realname']. ", ";
}
if(strlen($TrResponsavel)>2){
	$TrResponsavel = substr($TrResponsavel, 0, strlen($TrResponsavel)-2);
}
$SelAtendimento = "select max(date_format(date_mod, '%d/%m/%Y %H:%i')) as date_mod from glpi_logs where itemtype like 'Ticket' and id_search_option=12 and new_value=15 and items_id=".$TrId;
$ResDtAtendimento = $DB->query($SelAtendimento);
if($ResDtAtendimento){
	$dtatend = $DB->fetchAssoc($ResDtAtendimento);
	if($dtatend){
		$TrDataAtendimento = $dtatend['date_mod'];
	}	
}
$EntidadeId = $Ticket['entities_id'];
$SelEmpresa = "SELECT * FROM glpi_entities WHERE id = '".$EntidadeId."'";
$ResEmpresa = $DB->query($SelEmpresa);
$Empresa = $DB->fetchAssoc($ResEmpresa);
$EntidadeName = $Empresa['name'];
$EntidadeCep = $Empresa['postcode'];
$EntidadeEndereco = $Empresa['address'];
$EntidadeEmail = $Empresa['email'];
$EntidadePhone = $Empresa['phonenumber'];
// select entity rn
$SelEntityRn = "SELECT * FROM glpi_plugin_tr_rn WHERE entities_id = '".$EntidadeId."'";
$ResEntityRn = $DB->query($SelEntityRn);
$EntityRnQuery = $DB->fetchAssoc($ResEntityRn);
$EntityRn = $EntityRnQuery['rn']  ?? "";
$SelEmail = "SELECT * FROM glpi_useremails WHERE users_id = '".$TrUserId."'";
$ResEmail = $DB->query($SelEmail);
$Email = $DB->fetchAssoc($ResEmail);
$UserEmail = $Email['email'] ?? "";
$SelCustoLista = "SELECT actiontime, sec_to_time(actiontime) AS Hora,name,cost_time,cost_fixed,cost_material,FORMAT(cost_time,2,'de_DE') AS cost_time2, FORMAT(cost_fixed,2,'de_DE') AS cost_fixed2, FORMAT(cost_material,2,'de_DE') AS cost_material2, SUM(cost_material + cost_fixed + cost_time * actiontime/3600) AS CustoItem FROM glpi_ticketcosts WHERE tickets_id = '".$TrId."' GROUP BY id";
$ResCustoLista = $DB->query($SelCustoLista);
$SelCusto = "SELECT SUM(cost_material + cost_fixed + cost_time * actiontime/3600) AS SomaTudo FROM glpi_ticketcosts WHERE tickets_id = '".$TrId."'";
$ResCusto = $DB->query($SelCusto);
$Custo = $DB->fetchAssoc($ResCusto);
$CustoTotal =  $Custo['SomaTudo'] ?? 0;
$CustoTotalFinal = number_format($CustoTotal, 2, ',', ' ');
$SelTempoTotal = "SELECT SUM(actiontime) AS TempoTotal FROM glpi_ticketcosts WHERE tickets_id = '".$TrId."'";
$ResTempoTotal = $DB->query($SelTempoTotal);
$TempoTotal = $DB->fetchAssoc($ResTempoTotal);
$seconds = $TempoTotal['TempoTotal'];
$hours = floor($seconds / 3600);
$seconds -= $hours * 3600;
$minutes = floor($seconds / 60);
$seconds -= $minutes * 60;
$SelLocId = "SELECT locations_id FROM `glpi_tickets` WHERE id = '".$TrId."'";
$ResLocId = $DB->query($SelLocId);
$LocId = $DB->fetchAssoc($ResLocId);
$LocationsId = $LocId['locations_id'];
$SelNameLoc = "SELECT name FROM glpi_locations WHERE id = '".$LocationsId."'";
$ResNameLoc = $DB->query($SelNameLoc);
$Loc = $DB->fetchAssoc($ResNameLoc);
$Locations = $Loc['name']  ?? ""; 
$SelTicketUsers = "SELECT * FROM glpi_tickets_users WHERE tickets_id = '".$TrId."'";
$ResTicketUsers = $DB->query($SelTicketUsers);
$TicketUsers = $DB->fetchAssoc($ResTicketUsers);
$TrUserId = $TicketUsers['users_id'];
$SelUsers = "SELECT * FROM glpi_users WHERE id = '".$TrUserId."'";
$ResUsers = $DB->query($SelUsers);
$Users = $DB->fetchAssoc($ResUsers);
$UserName = $Users['firstname']. " " .$Users['realname'];
$UserCpf = $Users['registration_number'];
$UserTelefone = $Users['mobile'];
$UserEndereco = $Users['comment'];
$UserCep = $Users['phone2'];
$SelEmail = "SELECT * FROM glpi_useremails WHERE users_id = '".$TrUserId."'";
$ResEmail = $DB->query($SelEmail);
$Email = $DB->fetchAssoc($ResEmail);
$UserEmail = $Email['email'] ?? "";
// select itens
$SelItens = "SELECT * FROM glpi_items_tickets WHERE tickets_id = '".$TrId."'";
$ResItens = $DB->query($SelItens);
$ItensQuery = $DB->fetchAssoc($ResItens);
$ItemType = $ItensQuery['itemtype'] ?? "";
$ItensId = $ItensQuery['items_id'] ?? "";
// select items computers
$SelComputers = "SELECT * FROM glpi_computers WHERE id = '".$ItensId."'";
$ResSelComputers = $DB->query($SelComputers);
$ComputersQuery = $DB->fetchAssoc($ResSelComputers);
$ComputerName = $ComputersQuery['name'] ?? "";
$ComputerSerial = $ComputersQuery['serial'] ?? "";
// select items monitor
$SelMonitors = "SELECT * FROM glpi_monitors WHERE id = '".$ItensId."'";
$ResSelMonitors = $DB->query($SelMonitors);
$MonitorsQuery = $DB->fetchAssoc($ResSelMonitors);
$MonitorName = $MonitorsQuery['name'] ?? "";
$MonitorSerial = $MonitorsQuery['serial'] ?? "";
// select items printers
$SelPrinters = "SELECT * FROM glpi_printers WHERE id = '".$ItensId."'";
$ResSelPrinters = $DB->query($SelPrinters);
$PrintersQuery = $DB->fetchAssoc($ResSelPrinters);
$PrinterName = $PrintersQuery['name'] ?? "";
$PrinterSerial = $PrintersQuery['serial'] ?? "";