<h3 class="no-spacing">{$lang.admin.edit_user} <i>{$id|userData:'displayname'}</i> <a href="users" class="btn btn-danger pull-right">{$lang.back}</a></h3>
<hr />
<form class="form-horizontal half-width" method="post" action="../save_user/{$id}" enctype="multipart/form-data">
    <div class="form-group">
        <label class="col-sm-4 control-label" for="fullname">{$lang.fullname}</label>
        <div class="col-sm-6">
            <input id="fullname" name="fullname" value="{$user.displayname}" class="form-control"{if="$disabled"} disabled{/if} />
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-4 control-label" for="email">{$lang.email}</label>
        <div class="col-sm-6">
            <input id="email" name="email" value="{$user.email}" class="form-control"{if="$disabled"} disabled{/if} />
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-4 control-label" for="password">{$lang.new_password}</label>
        <div class="col-sm-6">
            <input id="password" name="password" placeholder="********" class="form-control" type="password"{if="$disabled"} disabled{/if} />
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-4 control-label" for="password_repeat">{$lang.new_password_repeat}</label>
        <div class="col-sm-6">
            <input id="password_repeat" name="password_repeat" placeholder="********" class="form-control" type="password"{if="$disabled"} disabled{/if} />
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-4 control-label">{$lang.project_fields.upload_picture}</label>
        <div class="col-sm-6">
            <input type="file" name="file" accept="image/*" />
            <img class="{if="!$hasImage"}hidden {/if}full-width auto-height form-control" id="imgpreview" src="{if="$hasImage"}../../content/avatar/{$id}.png{/if}" />
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
<script>
    $( 'input[name=file]' ).change( function( $event )
    {
        $( '#imgpreview' ).removeClass( 'hidden' ).attr( 'src', URL.createObjectURL( $event.target.files[0] ) );
    } );
</script>
