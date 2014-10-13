<h2>{$lang.projects}</h2>
{loop="$projects"}
<p class="project"><a href="project/{$key}">{$value.name}</a>: {$value.description}</p>
{/loop}
<h2>{$lang.issues}</h2>
{loop="latest_issues"}
	<p class="issue">
		<a href="issue/{$key}">{$value.name}</a> <small>({$value.description|substr:0,30}...)</small>
	</p>
{/loop}