<?php
/* Smarty version 3.1.33, created on 2021-07-15 11:56:35
  from 'C:\xampp\htdocs\FellowMw\tpl\index.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_60f006536dffd1_59532454',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'b8d432fb337deef6f291200126bd8e955cdbdb58' => 
    array (
      0 => 'C:\\xampp\\htdocs\\FellowMw\\tpl\\index.tpl',
      1 => 1626342984,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:tpl/docutype.tpl' => 1,
    'file:tpl/head.tpl' => 1,
    'file:tpl/header.tpl' => 1,
    'file:tpl/menu-container.tpl' => 1,
    'file:tpl/slider.tpl' => 1,
    'file:tpl/content.tpl' => 1,
    'file:tpl/back-to-top.tpl' => 1,
    'file:tpl/footer.tpl' => 1,
  ),
),false)) {
function content_60f006536dffd1_59532454 (Smarty_Internal_Template $_smarty_tpl) {
?><!--#INC(#REF(page.root?).'tpl/docutype.tpl.html'?)-->
<?php $_smarty_tpl->_subTemplateRender('file:tpl/docutype.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>
<html>
	<head>
	  <title>
		
		<?php echo $_smarty_tpl->tpl_vars['title']->value;?>

	  </title>
	  <?php $_smarty_tpl->_subTemplateRender('file:tpl/head.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>
	   <!-- #INC(#REF(page.root?).'tpl/head.tpl.html'?)-->
	</head>

	<body>
		<?php $_smarty_tpl->_subTemplateRender('file:tpl/header.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>
		
		<!--#INC(#REF(page.root?).'tpl/header.tpl.html'?)-->
		<?php $_smarty_tpl->_subTemplateRender('file:tpl/menu-container.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>
		
		<!--#INC(#REF(page.root?).'tpl/menu-container.tpl.html'?)--->
		
		<?php $_smarty_tpl->_subTemplateRender('file:tpl/slider.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>
		
		<!--#INC(#REF(page.root?).'tpl/slider.tpl.html'?)-->
		
		<?php $_smarty_tpl->_subTemplateRender('file:tpl/content.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

		<!--#INC(#REF(page.root?).'tpl/content.tpl.html'?)-->
		

		<!--#INC(#REF(page.root?).'tpl/under-content.tpl.html'?)-->
		<?php $_smarty_tpl->_subTemplateRender('file:tpl/back-to-top.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>
		
		<!--#INC(#REF(page.root?).'tpl/back-to-top.tpl.html'?)--->
		<?php $_smarty_tpl->_subTemplateRender('file:tpl/footer.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>
		<!--#INC(#REF(page.root?).'tpl/footer.tpl.html'?)--->
		
	</body>
</html><?php }
}
