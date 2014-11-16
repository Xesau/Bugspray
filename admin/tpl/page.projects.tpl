<h3 class="no-spacing">{$lang.admin.projects} {if="hasPermission( USERID, 'bs_projects' )"}<a href="new_project" class="btn btn-primary pull-right">{$lang.admin.new_project}</a>{/if}</h3>
<hr />
{if="hasPermission( USERID, 'bs_projects' )"}
{loop="$projects"}
<div class="entry">
    {if="file_exists( CDIR . '/content/project_imgs/' . $key . '.png' )"}
    <div class="pic pull-left">
        <img class="pull-left" src="{$settings.base_url}/content/project_imgs/{$key}.png?t={'' . CDIR . '/content/project_imgs/' . $key1 . '.' . 'png'|filemtime}" width=100>
        <a class="remove-pic btn btn-danger" href="remove_project_img/{$key}">x</a>
    </div>
    {else}
    <img class="pull-left" src="{$settings.base_url}/content/project_imgs/default.png" width=100>
    {/if}
    <div class="pull-left left-margin">
        <h3 class="no-spacing"><a href="project/{$value.id}"{if="$value.enabled == '0'"} class="disabled"{/if}>{$value.name}</a> <small>({$value.short})</small></h3>
        {$value.description}<br /><br />
        {$lang.lead}: {$value.project_lead|userData:'displayname'}
    </div>
    <div class="clear"></div>
</div>
{/loop}
{else}
{loop="$projects"}
<div class="entry">
    {if="file_exists( CDIR . '/content/project_imgs/' . $key . '.png' )"}
    <div class="pic pull-left">
        <img class="pull-left" src="{$settings.base_url}/content/project_imgs/{$key}.png?t={'' . CDIR . '/content/project_imgs/' . $key1 . '.' . 'png'|filemtime}" width=100>
    </div>
    {else}
    <img class="pull-left" src="{$settings.base_url}/content/project_imgs/default.png" width=100>
    {/if}
    <div class="pull-left left-margin">
        <h3 class="no-spacing"><a href="project/{$value.id}"{if="$value.enabled == '0'"} class="disabled"{/if}>{$value.name}</a> <small>({$value.short})</small></h3>
        {$value.description}<br /><br />
        {$lang.lead}: {$value.project_lead|userData:'displayname'}
    </div>
    <div class="clear"></div>
</div>
{/loop}
{/if}
{if="count($projects) == 0"}
{$lang.no_projects}
{/if}