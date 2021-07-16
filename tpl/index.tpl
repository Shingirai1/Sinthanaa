<!--#INC(#REF(page.root?).'tpl/docutype.tpl.html'?)-->
{include file='tpl/docutype.tpl'}
<html>
	<head>
	  <title>
		
		{$title}
	  </title>
	  {include file='tpl/head.tpl'}
	   <!-- #INC(#REF(page.root?).'tpl/head.tpl.html'?)-->
	</head>

	<body>
		{include file='tpl/header.tpl'}
		
		<!--#INC(#REF(page.root?).'tpl/header.tpl.html'?)-->
		{include file='tpl/menu-container.tpl'}
		
		<!--#INC(#REF(page.root?).'tpl/menu-container.tpl.html'?)--->
		
		{include file='tpl/slider.tpl'}
		
		<!--#INC(#REF(page.root?).'tpl/slider.tpl.html'?)-->
		
		{include file='tpl/content.tpl'}

		<!--#INC(#REF(page.root?).'tpl/content.tpl.html'?)-->
		

		<!--#INC(#REF(page.root?).'tpl/under-content.tpl.html'?)-->
		{include file='tpl/back-to-top.tpl'}
		
		<!--#INC(#REF(page.root?).'tpl/back-to-top.tpl.html'?)--->
		{include file='tpl/footer.tpl'}
		<!--#INC(#REF(page.root?).'tpl/footer.tpl.html'?)--->
		
	</body>
</html>