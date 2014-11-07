{include="template.projects"}
<div class="clearfix"></div>
<hr />
<h2>{$lang.issues}</h2>
{loop="latest_issues"}
<p class="issue">
	{$value.project|projectData:'short'}#{$key} <a href="issue/{$key}">{$value.name}</a> <small>({$value.description|substr:0,30}...)</small>
</p>
{/loop}
{if="count($latest_issues) == 0"}
{$lang.no_issues}
{/if}