<h3 class="no-spacing">{$lang.projects}</h3>
<hr />
{loop="$projects"}
<div class="entry">
    <div class="pic pull-left">
        <img class="pull-left padded" width=100 src="{$settings.base_url}/content/project_imgs/{if="file_exists(CDIR . '/content/project_imgs/'. $value.id . '.png')"}{$value.id}{else}default{/if}.png" />
    </div>
    <div class="pull-left left-margin">
        <h3 class="no-spacing"><a href="project/{$value.id}">{$value.name}</a> <small>({$value.short})</small></h3>
        {$value.description}<br /><br />
        {$lang.lead}: {$value.project_lead|userData:'displayname'}
    </div>
    <div class="clear"></div>
</div>
{/loop}
{if="count($projects) == 0"}
{$lang.no_projects}
{/if}