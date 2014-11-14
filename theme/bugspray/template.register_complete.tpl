<h3 class="no-spacing">{$lang.registration_completed}</h3>
<hr>
{$lang.thanks_for_registering}<br />
{$lang.enter_activation_code}
<hr>
<form class="form-inline" action="{$settings.base_url}/activate">
    <div class="form-group">
        <input type="hidden" name="id" value="{$userId}" />
        <input name="code" placeholder="12345" />
    </div>
</form>