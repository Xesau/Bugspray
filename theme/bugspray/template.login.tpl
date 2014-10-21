{if="!empty( $error ) && $error == 'login'"}
    <div class="error_message">
            {$lang.login_error.$login_error}
    </div>
{/if}
{if="!empty( $error ) && $error == 'register'"}
    <div class="error_message">
            {$lang.register_error.$register_error}
    </div>
{/if}
<form class="left" action="send/login" method="post">
	<h2>{$lang.login}</h2>
	<table>
		<tr>
			<td style="width:200px">{$lang.email}</td>
			<td style="width:200px"><input type="email" name="email"{if="!empty($email)"} value="{$email}"{/if}></td>
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
<form class="right" action="send/register" method="post">
    <h2>{$lang.register}</h2>
    <table>
		<tr>
			<td style="width:200px">{$lang.fullname}</td>
			<td style="width:200px"><input name="fullname"{if="!empty($fullname)"} value="{$fullname}"{/if}></td>
		</tr>
        <tr>
			<td style="width:200px">{$lang.email}</td>
			<td style="width:200px"><input type="email" name="email"{if="!empty($email)"} value="{$email}"{/if}></td>
		</tr>
		<tr>
			<td>{$lang.password}<br></td>
			<td><input type="password" name="password"></td>
		</tr>
        <tr>
			<td>{$lang.repeat_password}<br></td>
			<td><input type="password" name="repeat_password"></td>
		</tr>
		<tr>
			<td></td>
			<td><input type="submit" value="{$lang.register}"></td>
		</tr>
	</table>
</form>
<div class="clear"></div>