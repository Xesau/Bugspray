<form action="../send/newissue/{$project.id}" class="half-width form-horizontal" method="post">
		<legend><b>{$lang.new_issue}</b>: {$project.name}</legend>
        <div class="form-group">
            <label class="col-sm-4 control-label">{$lang.title}</label>
            <div class="col-sm-6"><input class="form-control" name="title" placeholder="{$lang.title}"></div>
        </div>
        {if="$settings.issue_security == 'true'"}<div class="form-group">
            <label class="col-sm-4 control-label">{$lang.security_level}</label>
            <div class="col-sm-6">
                <select class="form-control" name="security">
                    <option value="public" selected="selected">{$lang.public}</option>
                    <option value="private">{$lang.private}</option>
                </select>
            </div>
        </div>{/if}
        {if="$settings.issue_priority == 'true'"}<div class="form-group">
            <label class="col-sm-4 control-label">{$lang.priority}</label>
            <div class="col-sm-6">
                <select class="form-control" name="priority">
                    <option value="high">{$lang.high}</option>
                    <option value="medium" selected="selected">{$lang.medium}</option>
                    <option value="low">{$lang.low}</option>
                </select>
            </div>
        </div>{/if}
        {if="$settings.issue_project_version == 'true'"}<div class="form-group">
            <label class="col-sm-4 control-label">{$lang.version}</label>
            <div class="col-sm-6"><input class="form-control" name="version" placeholder="{$lang.version}"></div>
        </div>{/if}
        {if="$settings.issue_labels == 'true' && count( $issue_labels ) > 0 "}<div class="form-group">
            <label class="col-sm-4 control-label">{$lang.label}</label>
            <div class="col-sm-6">{loop="$issue_labels"}<input name="label" type="radio" value="{$key}"><span class="label robs" style="background-color: #{$value.bgcolor}; color: #{$value.txtcolor}">{$key}</span>{/loop}</div>
        </div>{/if}
        <div class="form-group">
            <label class="col-sm-4 control-label">{$lang.description}</label>
            <div class="col-sm-6">
                <textarea class="form-control full-width" rows=10 name="description"></textarea>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-offset-4 col-sm-6">
                <input type="submit" class="btn btn-primary" value="{$lang.save}" />
            </div>
        </div>
</form>