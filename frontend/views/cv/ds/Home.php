<?php
//
?>



<script>
window.addEventListener('load',function(){
  (function($){
	  //const quill = new Quill('.ql-container.el', {
		//theme: 'snow'
	  //});
	  /*
	  $('.ql-container').each(function() {
		new Quill(this);
	  });
	  */
	  /*quill.on('text-change', (delta, oldDelta, source) => {
		  if (source == 'api') {
			console.log('An API call triggered this change.');
		  } else if (source == 'user') {
			console.log('A user action triggered this change.');
			console.log(delta, oldDelta)
		  }
		});*/
		
  })(jQuery)
});
</script>

<script src="/gStatic/pako.min.js" ></script>
<script src="/gStatic/phUtils.js" ></script>
<script src="/gStatic/PH_Utils.UiBase.min.js"></script>
<link href="/gStatic/dd-content.css" rel="stylesheet">
<script src="/gStatic/dd-content.js" ></script>
<script src="/gStatic/dd-data.js"></script>


<link href="/gStatic/hwg.nav.css" rel="stylesheet">
<link href="/gStatic/site.css" rel="stylesheet">
<script src="/gStatic/wDesign.js"></script>



<style>

</style>

<div class="row">
	<div class="col col-sm-3">a
	</div>
	<div class="col w-design-root">
		<script>
		$('body').wDesign({
			rootDotClass: '.w-design-root',
			tplData: window.wTpl
		});
		</script>
	</div>
	<div class="col col-sm-3">a
	</div>
</div>


