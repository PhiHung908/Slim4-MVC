{* Smarty *}

{extends file=$layout??'layout.tpl'}

{block name=content}
<div class="row justify-content-center">
	<div class="col col-md-6 pt-5">
	
		<h2>Mẫu <small>(Smarty)</small> sử dụng csrf và escaper</h2>


{if !$smarty.post.testxss|isset}
<form class="row g-3" method="POST" action="{urlTo()}">
	{csrf_esc $csrf}
	<input type="hidden" name="{$csrf.keys.name}" value="{$csrf.name}">
	<input type="hidden" name="{$csrf.keys.value}" value="{$csrf.value}">
	
	<div class="col col-md-6 form-floating">
		<input type="text" class="form-control" id="testxss" name="testxss" placeholder=""/>
		<label for="testxss" >Input for test xss</label>
	</div>
	<button class="btn btn-primary" type="submit">submit</button>
</form>
{/if}

{if $smarty.post.testxss|isset}
	<p>
	<div> Posted: {$smarty.post.testxss} </div>
	</p>
{/if}

<p>&nbsp;</p>


		{if is_array($data)}
		<h5>All row</h5>
		<table border="1" width="100%"><thead><tr><td>ID</td><td>Name</td></tr></thead>
			{foreach from=$data item=oRow}
				<tr> <td>{$oRow->getId()}</td> <td>{$oRow->getName()}</td> </tr>
			{/foreach}
		</table>

		
		{else}
		<h5>One row</h5>
		<table border="1" width="100%"><thead><tr><td>ID</td><td>Name</td></tr></thead>
				<tr> <td>{$data->getId()}</td> <td>{$data->getName()}</td> </tr>
		</table>
		{/if}

<p>&nbsp;</p>


<p>
	<a href="{urlTo('list')}" >List all row in #TPL_PRODUCT#</a>
</p>

<p>
	<a href="{urlTo('row/2')}" >View row id=2</a>
</p>		



<p>
	<a href="{urlGo('http://google.com')}" >Redirect</a>
</p>

<p>
{esc_x('<script>alert("laminas")</script>', 'html')}
<p>hoac</p>
{'<script>alert("laminas")</script>'|esc_x:html}
</p>
{/block}


