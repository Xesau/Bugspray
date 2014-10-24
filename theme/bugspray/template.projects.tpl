<h2>{$lang.projects}</h2>
{loop="$projects"}
<div class="project">
    <img class="left" src="{$settings.base_url}/content/project_imgs/{if="file_exists(CDIR . '/content/project_imgs/'. $value.id . '.png')"}{$value.id}{else}default.png{/if}" />
    <div class="left">
        <h2><a href="project/{$value.id}">{$value.name}</a> <small>({$value.short})</small></h2>
        {$value.description}<br /><br />
        {$lang.lead}: {$value.project_lead|userData:'displayname'}
    </div>
    <div class="clear"></div>
</div>
{/loop}