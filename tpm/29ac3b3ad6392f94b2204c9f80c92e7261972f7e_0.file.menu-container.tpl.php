<?php
/* Smarty version 3.1.33, created on 2021-06-29 10:01:47
  from 'C:\xampp\htdocs\FellowMw\tpl\menu-container.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_60dad36b4a4f04_67404777',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '29ac3b3ad6392f94b2204c9f80c92e7261972f7e' => 
    array (
      0 => 'C:\\xampp\\htdocs\\FellowMw\\tpl\\menu-container.tpl',
      1 => 1624953699,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_60dad36b4a4f04_67404777 (Smarty_Internal_Template $_smarty_tpl) {
?><div id="menu-container">
  <div id="logo">
	  <!--<p>Logo</p>--->
	  <img src="images/stn logo.png" >
  </div>
  <a class="mobile-menu" href="#"><i class="material-icons">receipt</i></a>
  <div class="nav">
	  <ul>
		  <li><a href="../FellowMw/" class="active">Home</a></li>
		  <li class="Services">
				<a href="#" >Services</a>
				<div class="dropdown-content">
				  <a href="system/">System</a>
				  <a href="web/">Web</a>
				  <a href="networking/">Networking</a>
				  <a href="car-hire/">Car hire</a>
				</div>
		  </li>
		  <li class="About_us"><a href="#">About Us</a></li>
		  <li class="contact_us"><a href="#">Contacts</a></li>
		  <!--<li class="contact_us">Contacts</li>-->
	  </ul>
  </div>
</div><?php }
}
