<h2>{$lang.login}</h2>
<form action="send/login" method="post">
	{if="!empty( $error )"}
		<div class="error_message">
				{$lang.login_error.$login_error}
		</div>
	{/if}
	<table>
		<tr>
			<td style="width:200px">{$lang.email}</td>
			<td style="width:200px"><input type="email" name="email" {if="!empty($email)"}value="{$email}"{/if}></td>
		</tr>
		<tr>
			<td>{$lang.password}<br></td>
			<td><input type="password" name="password"></td>
		</tr>
		<tr>
			<td><a href="forgot">{$lang.forgot_password}</a></td>
			<td><input type="submit" value="{$lang.login}"></td>
		</tr>
	</table>
</form>