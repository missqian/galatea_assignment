{include file="header.tpl"}
<div id="floater"></div>
<div id="login-panel">
<form method="post">
	<h2>Login Panel</h2>
	{if {$Message|@count} != 0}
		<div id="login-message">
			{foreach $Message as $EachMessage}
				<p>{$EachMessage}</p>
			{/foreach}
		</div>
	{/if}

	<label>User Name</label><br />
	<input type="text" name="name"><br />
	<label>Password</label><br />
	<input type="password" name="password"><br />
	<input type="submit" value="Submit"><br />
</div>

{include file="footer.tpl"}
