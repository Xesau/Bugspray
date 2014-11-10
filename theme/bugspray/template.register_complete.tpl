<h3 class="no-spacing">{$lang.registration_completed}</h3>
<hr>
{$lang.check_your_email_for_the_activation_code}
<hr>
<form class="form-inline" action="{$settings.base_url}/activate">
    <div class="form-group">
        <input type="hidden" name="id" value="{$userId}" />
        <input name="code" placeholder="12345" />
    </div>
</form>