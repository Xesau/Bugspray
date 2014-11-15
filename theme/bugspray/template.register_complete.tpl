<h3 class="no-spacing">{$lang.registration_completed}</h3>
<hr>
{$lang.thanks_for_registering}<br />
{$lang.enter_activation_code}
<hr>
<form class="form-inline" action="{$settings.base_url}/activate" method="post">
    <div class="form-group">
        <input class="form-control" name="id" placeholder="12345" />
        <input type="submit" value="{$lang.continue}" class="btn btn-primary">
    </div>
</form>