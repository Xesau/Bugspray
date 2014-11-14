<h3 class="no-spacing">{$lang.admin.edit_user} <i>{$id|userData:'displayname'}</i> <a href="users" class="btn btn-danger pull-right">{$lang.back}</a></h3>
<hr />
<form class="form-horizontal half-width">
    <div class="form-group">
        <label class="col-sm-4 control-label" for="username">{$lang.fullname}</label>
        <div class="col-sm-4">
            <input id="username" name="username" value="{$user.displayname}" class="form-control"{if="$disabled"} disabled{/if} />
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-4 control-label" for="email">{$lang.email}</label>
        <div class="col-sm-4">
            <input id="email" name="email" value="{$user.email}" class="form-control"{if="$disabled"} disabled{/if} />
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-4 control-label" for="passwordinput">{$lang.new_password}</label>
        <div class="col-sm-4">
            <input id="password" name="password" placeholder="********" class="form-control" type="password"{if="$disabled"} disabled{/if} />
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-4 control-label" for="passwordinput">{$lang.new_password_repeat}</label>
        <div class="col-sm-6">
            <input id="password_repeat" name="password_repeat" placeholder="********" class="form-control" type="password"{if="$disabled"} disabled{/if} />
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-4">
            <a href="banuser/{$id}" class="btn btn-danger pull-right"{if="!hasPermission( USERID, 'bs_ban' )"} disabled{/if}>{$lang.ban}</a>
        </div>
        <div class="col-sm-6">
            <input type="submit" class="btn btn-success" value="{$lang.save}"{if="$disabled"} disabled{/if} />
        </div>
    </div>
</form>
