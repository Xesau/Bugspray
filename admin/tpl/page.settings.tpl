<h3 class="no-spacing">{$lang.admin.settings}</h3>
<hr />
<form method="post" action="save_settings" class="form-horizontal half-width nofix" autocomplete="off">
    <div class="form-group">
        <label class="col-sm-4 control-label">
            {$lang.site_name}
        </label>
        <div class="col-sm-6">
            <input class="form-control" name="site_name" value="{$settings.site_name}"{if="!hasPermission( USERID, 'bs_update_settings' )"} disabled{/if} />
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-4 control-label">
            {$lang.base_url}
        </label>
        <div class="col-sm-6">
            <input class="form-control" name="base_url" value="{$settings.base_url}"{if="!hasPermission( USERID, 'bs_update_settings' )"} disabled{/if} />
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-4 control-label">
            {$lang.admin_email}
        </label>
        <div class="col-sm-6">
            <input class="form-control" name="admin_email" type="email" value="{$settings.admin_email}"{if="!hasPermission( USERID, 'bs_update_settings' )"} disabled{/if} />
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-4 control-label">
            {$lang.debug_mode}
        </label>
        <div class="col-sm-6">
            <select class="form-control" name="debug_mode"{if="!hasPermission( USERID, 'bs_update_settings' )"} disabled{/if}>
                <option value="true"{if="$settings.debug_mode == 'true'"} selected{/if}>{$lang.on}</option>
                <option value="false"{if="$settings.debug_mode == 'false'"} selected{/if}>{$lang.off}</option>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-4 control-label">
            {$lang.use_labels}?
        </label>
        <div class="col-sm-6">
            <select class="form-control" name="issue_labels"{if="!hasPermission( USERID, 'bs_update_settings' )"} disabled{/if}>
                <option value="true"{if="$settings.issue_labels == 'true'"} selected{/if}>{$lang.use}</option>
                <option value="false"{if="$settings.issue_labels == 'false'"} selected{/if}>{$lang.dont_use}</option>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-4 control-label">
            {$lang.use_priority}?
        </label>
        <div class="col-sm-6">
            <select class="form-control" name="issue_priority"{if="!hasPermission( USERID, 'bs_update_settings' )"} disabled{/if}>
                <option value="true"{if="$settings.issue_priority == 'true'"} selected{/if}>{$lang.use}</option>
                <option value="false"{if="$settings.issue_priority == 'false'"} selected{/if}>{$lang.dont_use}</option>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-4 control-label">
            {$lang.use_project_version}?
        </label>
        <div class="col-sm-6">
            <select class="form-control" name="version"{if="!hasPermission( USERID, 'bs_update_settings' )"} disabled{/if}>
                <option value="true"{if="$settings.issue_project_version == 'true'"} selected{/if}>{$lang.use}</option>
                <option value="false"{if="$settings.issue_project_version == 'false'"} selected{/if}>{$lang.dont_use}</option>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-4 control-label">
            {$lang.use_security}?
        </label>
        <div class="col-sm-6">
            <select class="form-control" name="security"{if="!hasPermission( USERID, 'bs_update_settings' )"} disabled{/if}>
                <option value="true"{if="$settings.issue_security == 'true'"} selected{/if}>{$lang.use}</option>
                <option value="false"{if="$settings.issue_security == 'false'"} selected{/if}>{$lang.dont_use}</option>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-4 control-label">
            {$lang.theme}
        </label>
        <div class="col-sm-6">
            <select class="form-control" name="theme"{if="!hasPermission( USERID, 'bs_update_settings' )"} disabled{/if}>
                {loop="$server.available_themes"}
                <option value="{$value|basename}"{if="$settings.theme == $value"} selected{/if}>{$value|basename}</option>{/loop}
            </select>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-4 control-label">
            {$lang.language}
        </label>
        <div class="col-sm-6">
            <select class="form-control" name="language"{if="!hasPermission( USERID, 'bs_update_settings' )"} disabled{/if}>
                {loop="$server.available_languages"}
                <option value="{$value|basename:'.lang.php'}"{if="$settings.language == basename( $value, '.lang.php' )"} selected{/if}>{$value|basename:'.lang.php'}</option>{/loop}
            </select>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-6 col-sm-offset-4">
            <input class="btn btn-primary" type="submit" value="{$lang.save}"{if="!hasPermission( USERID, 'bs_update_settings' )"} disabled{/if} />
        </div>
    </div>

</form>