<form action="../send/newissue/{$project.id}" method="post">
	<fieldset class="padded">
		<legend><b>{$lang.new_issue}</b>: {$project.name}</legend>
		<table>
			<tr>
				<td style="width: 300px;">{$lang.title}</td>
				<td style="width: 600px;"><input name="title" placeholder="{$lang.title}"></td>
			</tr>
			{if="$settings.issue_security == 'true'"}<tr>
				<td>{$lang.security_level}</td>
				<td>
					<select name="security">
						<option value="public" selected="selected">{$lang.public}</option>
						<option value="private">{$lang.private}</option>
					</select>
				</td>
			</tr>{/if}
			{if="$settings.issue_priority == 'true'"}<tr>
				<td>{$lang.priority}</td>
				<td>
					<select name="priority">
						<option value="high">{$lang.high}</option>
						<option value="medium" selected="selected">{$lang.medium}</option>
						<option value="low">{$lang.low}</option>
					</select>
				</td>
			</tr>{/if}
			{if="$settings.issue_project_version == 'true'"}<tr>
				<td>{$lang.version}</td>
				<td><input name="version" placeholder="{$lang.version}"></td>
			</tr>{/if}
			{if="$settings.issue_labels == 'true' && size( $issue_labels ) > 0 "}<tr>
				<td>{$lang.label}</td>
				<td>{loop="$issue_labels"}<input name="label" type="radio" value="{$key}"><span class="label" style="background-color: #{$value.bgcolor}; color: #{$value.txtcolor}">{$key}</span>{/loop}</td>
			</tr>{/if}
			<tr>
				<td>{$lang.description}</td>
				<td>
					<textarea rows=10 name="description"></textarea>
				</td>
			</tr>
			<tr>
				<td></td>
				<td>
					<button>{$lang.save}</button>
				</td>
			</tr>
		</table>
	</fieldset>
</form>