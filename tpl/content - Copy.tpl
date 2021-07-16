<div class="content-box" class="hide">
 <h1 class="services_h">SERVICES</h1>

{foreach from=$content key=k item=v}

 <div class="cells" id="{$v.element}">
	 <div class="top-box">
		<div class="icon-box">
		  <i class="material-icons">{$v.icon}</i>
		</div>
		<div class="head-box">
		  <h1>{$v.heading}</h1>
		</div>
	 </div>
	 <div class="body-box">
	   <p>{$v.paragraph}</p>
	 </div>
 </div>
 {/foreach}

</div>
