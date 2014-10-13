<?php if(!class_exists('raintpl')){exit;}?><h2><?php echo $lang["login"];?></h2>
<form action="send/login" method="post">
	<?php if( !empty( $error ) ){ ?>
		<div class="error_message">
				<?php echo $lang["login_error"]["$login_error"];?>
		</div>
	<?php } ?>
	<table>
		<tr>
			<td style="width:200px"><?php echo $lang["email"];?></td>
			<td style="width:200px"><input type="email" name="email" <?php if( !empty($email) ){ ?>value="<?php echo $email;?>"<?php } ?>></td>
		</tr>
		<tr>
			<td><?php echo $lang["password"];?><br></td>
			<td><input type="password" name="password"></td>
		</tr>
		<tr>
			<td><a href="http://localhost/issues2/forgot"><?php echo $lang["forgot_password"];?></a></td>
			<td><input type="submit" value="<?php echo $lang["login"];?>"></td>
		</tr>
	</table>
</form>