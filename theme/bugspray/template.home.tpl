{include="template.projects"}
<div class="clearfix"></div>
<hr />
<h3>{$lang.issues}</h3>
<hr />
{loop="latest_issues"}
<p class="issue">
	{$value.project|projectData:'short'}#{$key} <a href="issue/{$key}">{$value.name}</a> <small>({$value.description|substr:0,30}...)</small>
</p>
{/loop}
{if="count($latest_issues) == 0"}
{$lang.no_issues}
{/if}