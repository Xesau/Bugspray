<h3 class="no-spacing">{$lang.admin.projects}</h3>
<hr />
{loop="$projects"}
<div class="entry">
    #{$key}: {$value.name}
</div>
{/loop}