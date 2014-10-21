<h2 class="left">{$lang.project}: {$project.name}</h2>
<a href="{if="$loggedIn"}../newissue/{$project.id}{else}../login{/if}" class="button right">{$lang.new_issue}</a>
<div class="clear"></div>
<div class="description">
{$project.description}
</div>
{loop="latest_issues"}
	<p class="issue">
		<a href="issue/{$key}">{$value.name}</a> <small>({$value.description|substr:0,30}...)</small>
	</p>
{/loop}