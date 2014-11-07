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
<form class="pull-left form-horizontal half-width" action="send/login" method="post">
	<h2 class="no-spacing">{$lang.login}</h2>
   <hr />
    <div class="form-group">
        <label class="col-sm-4 control-label">{$lang.email}</label>
        <div class="col-sm-6"><input class="form-control" type="email" name="email"{if="!empty($email)"} value="{$email}"{/if}></div>
    </div>
    <div class="form-group">
        <label class="col-sm-4 control-label">{$lang.password}<br></label>
        <div class="col-sm-6"><input class="form-control" type="password" name="password"></div>
    </div>
    <div class="form-group">
        <label class="col-sm-4 control-label"><a href="forgot">{$lang.forgot_password}</a></label>
        <div class="col-sm-4"><input class="btn btn-primary" type="submit" value="{$lang.login}"></div>
    </div>
</form>
<form class="pull-left form-horizontal half-width" autocomplete="off"  action="send/register" method="post">
    <h2 class="no-spacing">{$lang.register}</h2>
    <hr />
    <div class="form-group">
        <label class="col-sm-4 control-label">{$lang.fullname}</label>
        <div class="col-sm-6"><input class="form-control" name="fullname"{if="!empty($fullname)"} value="{$fullname}"{/if}></div>
    </div>
    <div class="form-group">
        <label class="col-sm-4 control-label">{$lang.email}</label>
        <div class="col-sm-6"><input class="form-control" type="email" name="email"{if="!empty($email)"} value="{$email}"{/if}></div>
    </div>
    <div class="form-group">
        <label class="col-sm-4 control-label">{$lang.password}<br></label>
        <div class="col-sm-4"><input class="form-control" type="password" name="password"></div>
    </div>
    <div class="form-group">
        <label class="col-sm-4 control-label">{$lang.repeat_password}<br></label>
        <div class="col-sm-4"><input class="form-control" type="password" name="repeat_password"></div>
    </div>
    <div class="form-group">
        <div class="col-sm-6 col-sm-offset-4"><input class="btn btn-primary" type="submit" value="{$lang.register}"></div>
    </div>
</form>
<div class="clear"></div>