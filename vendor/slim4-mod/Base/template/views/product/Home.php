<?php
//
?>

<div class="row justify-content-center">
	<div class="col col-md-6 pt-5">

		<h2>Mẫu <small>(PHP)</small> sử dụng csrf và escaper</h2>

	
		<?php 
		if (!isset($_POST) || empty($_POST["testxss"])): ?>
		<form class="row g-3" method="POST" action="<?=$this->urlTo()?>">
			<?=$this->csrf_esc()?>
			<div class="col col-md-6 form-floating">
				<input class="form-control" type="text" id="testxss" placeholder="" name="testxss">
				<label for="testxss" >Input for test xss</label>
			</div>				
				<button class="btn btn-primary" type="submit">submit</button>
		</form>
		<?php endif?>

		<?php
		  if (!empty($_POST)) {
			echo '<div> Posted: ' . $this->esc_x($_POST['testxss'],'html') . '</div>';
		  }
		?>
		
		<p></p>
		
		
		<?php if (is_array($data)) { ?>
		<h5>All row</h5>
		<table border="1" width="100%"><thead><tr><td>ID</td><td>Name</td></tr></thead>
			<?php foreach($data as $oRow): ?>
				<tr> <td><?=$oRow->getId()?></td> <td><?=$oRow->getName()?></td> </tr>
			<?php endforeach ?>
		</table>

		<?php }else{?>

		<h5>One row</h5>
		<table border="1" width="100%"><thead><tr><td>ID</td><td>Name</td></tr></thead>
				<tr> <td><?=$data->getId()?></td> <td><?=$data->getName()?></td> </tr>
		</table>
		<?php } ?>
		
		<p>&nbsp;</p>
		
		<p>
			<a href="<?= $this->urlTo('row/2') ?>" >View row id=2</a>
		</p>		


		<p>
			<a href="<?= $this->urlTo('list') ?>" >List all row in product</a>
		</p>		

		<p>
			<a href="<?= $this->urlGo('http://google.com') ?>" >Redirect to Google</a>
		</p>

		<p>
		Script has escaper: <pre>
			<?=$this->esc_x('<script>alert("laminas")</script>','html')?>
			</pre>
		</p>

	</div>
</div>