<div><img class="left" src="{$settings.base_url}/content/project_imgs/{if="file_exists(CDIR . '/content/project_imgs/'. $project.id . '.png')"}{$value.id}{else}default.png{/if}" />
    <p class="right">
        <a href="{if="$loggedIn"}../newissue/{$project.id}{else}../login{/if}" class="button right">{$lang.new_issue}</a>
        <h2>{$lang.project}: {$project.name}</h2>
        {$project.description}
        <div class="clear">{$lang.lead}: {$value.project_lead|userData:name}</div>
    </p>
    <div class="clear"></div>
</div>
<h3>{$lang.issues}</h3>
{loop="latest_issues"}
	<p class="issue">
		<a href="issue/{$key}">{$value.name}</a> <small>({$value.description|substr:0,30}...)</small>
	</p>
{/loop}