<?php if(!class_exists('raintpl')){exit;}?><form action="../send/newissue/<?php echo $project["id"];?>" method="post">
	<fieldset class="padded">
		<legend><b><?php echo $lang["new_issue"];?></b>: <?php echo $project["name"];?></legend>
		<table>
			<tr>
				<td style="width: 300px;"><?php echo $lang["title"];?></td>
				<td style="width: 600px;"><input name="title" placeholder="<?php echo $lang["title"];?>"></td>
			</tr>
			<?php if( $settings["issue_security"] == 'true' ){ ?><tr>
				<td><?php echo $lang["security_level"];?></td>
				<td>
					<select name="security">
						<option value="public" selected="selected"><?php echo $lang["public"];?></option>
						<option value="private"><?php echo $lang["private"];?></option>
					</select>
				</td>
			</tr><?php } ?>
			<?php if( $settings["issue_priority"] == 'true' ){ ?><tr>
				<td><?php echo $lang["priority"];?></td>
				<td>
					<select name="priority">
						<option value="high"><?php echo $lang["high"];?></option>
						<option value="medium" selected="selected"><?php echo $lang["medium"];?></option>
						<option value="low"><?php echo $lang["low"];?></option>
					</select>
				</td>
			</tr><?php } ?>
			<?php if( $settings["issue_project_version"] == 'true' ){ ?><tr>
				<td><?php echo $lang["version"];?></td>
				<td><input name="version" placeholder="<?php echo $lang["version"];?>"></td>
			</tr><?php } ?>
			<?php if( $settings["issue_labels"] == 'true' ){ ?><tr>
				<td><?php echo $lang["label"];?></td>
				<td><?php $counter1=-1; if( isset($issue_labels) && is_array($issue_labels) && sizeof($issue_labels) ) foreach( $issue_labels as $key1 => $value1 ){ $counter1++; ?><input name="label" type="radio" value="<?php echo $key1;?>"><span class="label" style="background-color: #<?php echo $value1["bgcolor"];?>; color: #<?php echo $value1["txtcolor"];?>"><?php echo $key1;?></span><?php } ?></td>
			</tr><?php } ?>
			<tr>
				<td><?php echo $lang["description"];?></td>
				<td>
					<textarea rows=10 name="description"></textarea>
				</td>
			</tr>
			<tr>
				<td></td>
				<td>
					<button><?php echo $lang["save"];?></button>
				</td>
			</tr>
		</table>
	</fieldset>
</form>