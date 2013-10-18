{include file="header.tpl"}
<div id="container">
	<div id="people">
		<div id="add-new-div"><a id="add-new">Add New</a><a id="logout" href="logout.php">Log Out</a></div>
		<div id="index-message">{$Message}</div>
		<div id="add-new-form">
		</div>
		<h4>Users</h4>
		<table>
		<tr class="row-head">
			<th class="col-even">Name</th>
			<th class="col-odd">Type</th>
			<th class="col-even">Description</th>
			<th class="col-odd">Operations</th>
		</tr>
		{$line = 0}
		{foreach $People as $EachPerson}
			{$class = even}
			{if $line % 2}
				{$class = odd}
			{/if}

			<tr class="row-{$class}">
				<td class="col-even">{$EachPerson['name']}</th>
				<td class="col-odd">{$EachPerson['privilege']}</th>
				<td class="col-even">{$EachPerson['other']}</th>
				<td class="col-odd">
					{if {$User['privilege'] == 0} || {$User['id']} == $EachPerson['id']}
					<a class="edit operation" id="{$EachPerson['id']}">Edit</a>
					{/if}
					{if {$User['privilege'] == 0}}
					<a href="delete.php?id={$EachPerson['id']}" class="delete operation">Delete</delete>
					{/if}
				</th>
			</tr>
			{$line = $line + 1}

		{/foreach}

		</table>
	</div>
</div>

{include file="footer.tpl"}
