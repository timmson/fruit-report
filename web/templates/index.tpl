<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset={$const.global.encodingHTML}"/>
    <meta http-equiv="Pragma" content="no-cache"/>
    <title>{$const.admin.name}&nbsp;{$const.admin.major}{$const.admin.minor} - {$currentdep.descr}</title>
    <link rel="shortcut icon" href="{$factory->img_admin_dir}favicon.ico"/>
    <script src="{$factory->js_dir}jquery.min.js" type="text/javascript"></script>
    <script src="{$factory->js_dir}index.js" type="text/javascript"></script>
    <script src="{$factory->js_dir}ajaxcore.js" type="text/javascript"></script>
    <style type="text/css">
    {literal}
	h1 {
    	font-size:22pt;
	}

	h2 {
	    font-size:18pt;
	}

	h3 {
	    font-size:16pt;
	}

	a {
	  font-size:10pt;
	  color:#000;
	  text-decoration:none;
	}

	a:hover {
	   text-decoration:underline;
	}

	a.av {
	  text-decoration:none;
	}


	a.unav {
	    color:#ccc;
	    cursor:help;
	    text-decoration:none;	    	
	}

	a.cur {    
	    font-weight:bold;
	    text-decoration:none;
	}
	body, html {
	  margin:0px; 
	  padding:10px; 
	  border: 0px;
	  font-family: Trebuchet MS;
	  min-width:950px;
	  font-size:10pt;
	}

	img {
	    border:0px;
	}


	td.skeleton {
		   border: 1px solid #ccc;	
	}

	td.menu {
	   border-bottom: 1px solid #ccc;
	}


	div.debug-info
	{
	    display:none; 
	    border: 1px solid #CCC; 
	    font-size:8pt; 
	    position:absolute; 
	    background: #fff;
	    height:800px;
	    overflow:auto;
	}

	input.corp
	{
		border: 1px solid #ccc; 
		color:#000; 
		font-weight:bold; 
		background: #F9F300;
	}

	input.corp_text
	{
		border: 1px solid #ccc; 
		color:#000; 
		font-weight:bold; 
	}

	select.corp
	{
		border: 1px solid #ccc; 
		color:#000; 
		font-weight:bold; 
	}

	table.plain {
		border-collapse:collapse;
		padding:0px;
		width:100%;
	}

	table.plain td {
		padding:2px;
	}

	table.container {
		border:1px solid #ccc;
		border-collapse:collapse;
		padding:0px;
		width:100%;
	}

	table.container td, th{
		border:1px solid #ccc;
		padding:2px;
		text-align:center;
	}
    {/literal}
    </style>
</head>
<body>
    {if $smarty.request.mode eq 'async'}
		{include file="$page"}
    {else}
		<table width= "100%" cellspacing="0" cellpadding="0" border="0">
			<tr>
		   <td rowspan="1" style="text-align:left; vertical-align:middle; width:150px;">
			<a href="javascript:debugInfo(document.getElementById('debuginfo'));" id="buglink">
				<img src="{$factory->img_admin_dir}admin_bug.gif" alter="[debug]" title="Æææææ!"/>
			</a>
			<a href="javascript:window.location=window.location + '&mode=async&amp;oper=doc';" id="exportdoc">
				<img src="{$factory->img_admin_dir}exportdoc.png" alter="[exportdoc]" title="Åõïîðò â DOC"/>
			</a>
			<a href="javascript:window.location=window.location + '&mode=async&amp;oper=xls';" id="exportxls">
				<img src="{$factory->img_admin_dir}exportxls.png" alter="[exportxls]" title="Åõïîðò â XLS"/>
			</a>
			<div id="debuginfo" class="debug-info" style="text-align:left;">
			{section name=i loop=$factory->debugs}
				{$factory->debugs[i]}<br/>
			{/section}
			</div>
			</td>
			<td>
			{include file="deps.tpl"}
			</td>
			</tr>
		</table>
		<div id="pagecontainer" style="border: 1px dashed #CCC; padding: 10px 10px 10px 10px; display: inline-block; width:100%">
			{include file="$page"}
		</div>
		<div align="right" style="color:#CCc; font-size:8pt;">
			&copy;{$const.global.copyright}&nbsp;{$const.global.developer}
		</div>
    {/if}
</body>
</html>
