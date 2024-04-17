<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Upload de arquivos</title>
</head>

<body>
<div style="width:55%; margin:auto;">
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
if(isset($_FILES['arquivo']['name']) && $_FILES["arquivo"]["error"] == 0)
{

	echo "Você enviou o arquivo: <strong>" . $_FILES['arquivo']['name'] . "</strong><br />";
	echo "Este arquivo é do tipo: <strong>" . $_FILES['arquivo']['type'] . "</strong><br />";
	echo "Temporáriamente foi salvo em: <strong>" . $_FILES['arquivo']['tmp_name'] . "</strong><br />";
	echo "Seu tamanho é: <strong>" . $_FILES['arquivo']['size'] . "</strong> Bytes<br /><br />";

	$arquivo_tmp = $_FILES['arquivo']['tmp_name'];
	$nome = $_FILES['arquivo']['name'];
	$extensao = strrchr($nome, '.');
	$extensao = strtolower($extensao);
	if(strstr('.jpg;.jpeg;.gif;.png', $extensao))
	{
		$novoNome = "logo_tr.png";
		$destino = '../pics/' . $novoNome; 
		if( @move_uploaded_file( $arquivo_tmp, $destino  ))
		{
			echo "Arquivo salvo com sucesso em : <strong>" . $destino . "</strong><br />";
			echo "<img src=\"" . $destino . "\" />";
			echo '<br><p> <a href="#" class="vsubmit" onclick="history.back();"> Voltar </a>';
		}
		else
			echo "Erro ao salvar o arquivo. Aparentemente você não tem permissão de escrita.<br />";
	}
	else
		echo "Você poderá enviar apenas arquivos \"*.jpg;*.jpeg;*.gif;*.png\"<br />";
}
else
{
	echo "Você não enviou nenhum arquivo!";
	echo "<script language='javascript'>history.back()</script>";
} 
?>
</div>
</body>
</html>