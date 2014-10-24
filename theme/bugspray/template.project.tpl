<div><img class="left" src="{$settings.base_url}/content/project_imgs/{if="file_exists(CDIR . '/content/project_imgs/'. $project.id . '.png')"}{$value.id}{else}default.png{/if}" />
    <p class="right">
        <a href="{if="$loggedIn"}../newissue/{$project.id}{else}../login{/if}" class="button right">{$lang.new_issue}</a>
        <h2>{$lang.project}: {$project.name}</h2>
        {$project.description}<br /><br />
        {$lang.lead}: {$project.project_lead|userData:'displayname'}
        <div class="clear"></div>
    </p>
    <div class="clear"></div>
</div>
<hr />
<h3>{$lang.issues}</h3>
{loop="latest_issues"}
	<p class="issue">
        {$value.project|projectData:'short'}#{$key} <a href="issue/{$key}">{$value.name}</a> <small>({$value.description|substr:0,30}...)</small>
	</p>
{/loop}
{if="count($latest_issues) == 0"}{$lang.no_issues}{/if}