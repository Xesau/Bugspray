<h3 class="no-spacing">{$lang.login}</h3>
<form method="post" action="send/login" class="form-horizontal">
    <div class="form-group">
        <label class="col-sm-2 control-label">{$lang.email}</label>
        <div class="col-sm-3">
            <input class="form-control" name="email" type="email" />
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label">{$lang.password}</label>
        <div class="col-sm-3">
            <input type="password" class="form-control" name="password" />
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-3 col-sm-offset-2">
            <input type="submit" class="btn btn-primary" value="{$lang.login}" />
        </div>
    </div>
</form>