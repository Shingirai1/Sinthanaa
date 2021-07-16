<?php
/* Smarty version 3.1.33, created on 2021-06-15 13:09:59
  from 'C:\xampp\htdocs\FellowMw\tpl\content.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_60c88a876b5897_56749352',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '4684f326bcfa36aec40defa52a21c2d43b70a4c5' => 
    array (
      0 => 'C:\\xampp\\htdocs\\FellowMw\\tpl\\content.tpl',
      1 => 1623755392,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_60c88a876b5897_56749352 (Smarty_Internal_Template $_smarty_tpl) {
?><div class="content-box" class="hide">
 <h1 class="services_h">SERVICES</h1>

<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['content']->value, 'v', false, 'k');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['k']->value => $_smarty_tpl->tpl_vars['v']->value) {
?>

 <div class="cells" id="<?php echo $_smarty_tpl->tpl_vars['v']->value['element'];?>
">
	 <div class="top-box">
		<div class="icon-box">
		  <i class="material-icons"><?php echo $_smarty_tpl->tpl_vars['v']->value['icon'];?>
</i>
		</div>
		<div class="head-box">
		  <h1><?php echo $_smarty_tpl->tpl_vars['v']->value['heading'];?>
</h1>
		</div>
	 </div>
	 <div class="body-box">
	   <p><?php echo $_smarty_tpl->tpl_vars['v']->value['paragraph'];?>
</p>
	 </div>
 </div>
 <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>

</div>
<?php }
}
