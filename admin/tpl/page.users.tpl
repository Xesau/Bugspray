<h3 class="no-spacing">{$lang.admin.users}</h3>
<hr />
{loop="users"}
<div class="entry">
    <a href="user/{$key}">{$value.displayname}</a>
</div>
{/loop}