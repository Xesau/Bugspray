{include="template.projects"}
<h2>{$lang.issues}</h2>
{loop="latest_issues"}
	<p class="issue">
		{$value.project|projectData:'short'}#{$key} <a href="issue/{$key}">{$value.name}</a> <small>({$value.description|substr:0,30}...)</small>
	</p>
{/loop}