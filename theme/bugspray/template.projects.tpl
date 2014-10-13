<h2>{$lang.projects}</h2>
{loop="$projects"}
<p class="project"><a href="project/{$key}">{$value.name}</a>: {$value.description}</p>
{/loop}