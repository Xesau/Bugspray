<h3 class="no-spacing">{$lang.admin.users}</h3>
<hr />
{loop="users"}
<div class="entry">
    {if="file_exists( CDIR . '/content/avatar/' . $key . '.png' )"}
    <div class="pic pull-left">
        <img class="pull-left" src="../../content/avatar/{$key}.png?t={'' . CDIR . '/content/avatar/' . $key1 . '.' . 'png'|filemtime}" width=100>
        <a class="remove-pic btn btn-danger" href="remove_avatar/{$key}">x</a>
    </div>
    {else}
    <img class="pull-left" src="../../content/avatar/default.png" width=100>
    {/if}
    <div class="pull-left left-margin">
        <a href="user/{$key}"><b>{$value.displayname}</b></a><br />
        {$lang.email}: {$value.email}<br /><br />
        {$lang.last_login}: {if="$value.last_login < 0"}{$lang.never}{else}{'d-m-y h:i:s'|date:$value.last_login} (IP: {if="$value.last_ip =='::1'"}localhost{else}{$value.last_ip}{/if}){/if}<br />
        {$lang.register_ip}: {if="$value.register_ip =='::1'"}localhost{else}{$value.register_ip}{/if}
    </div>
</div>
{/loop}