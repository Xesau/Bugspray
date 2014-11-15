<!DOCTYPE html>

<html>
    <head>
        <title>{$settings.site_name|strip_tags} &bull; {$pagedata.title}</title>
        <link rel="stylesheet" href="{$themepath}/css/bootstrap.min.css" />
        <link rel="stylesheet" href="{$themepath}/css/style.css?a" />
        <script src="http://code.jquery.com/jquery-1.11.0.min.js"></script>
        <link rel="stylesheet" href="http://code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css">
        <script src="http://code.jquery.com/ui/1.11.2/jquery-ui.js"></script>
        <script src="{$themepath}/js/bootstrap.min.js"></script>
{loop="pagedata.js"}
        <script src="{$value}"></script>
{/loop}
{loop="pagedata.css"}
        <link rel="stylesheet" href="{$value}" />
{/loop}
    </head>
    
    <body>
        <section class="sidebar">
            <nav>
                <ul class="no-spacing">
                    {if="LOGGED_IN && hasPermission( USERID, 'bs_admin' )"}
                    <li><a href="{$admin}"{if="$page == 'home'"} class="current"{/if}><i class="glyphicon glyphicon-home glfw"></i>{$lang.admin.home}</a></li>
                    <li><a href="{$admin}/settings"{if="$page == 'settings'"} class="current"{/if}><i class="glyphicon glyphicon-cog glfw"></i>{$lang.admin.settings}</a></li>
                    <li><a href="{$admin}/labels"{if="$page == 'labels'"} class="current"{/if}><i class="glyphicon glyphicon-tags glfw"></i>{$lang.admin.labels}</a></li>
                    <li><a href="{$admin}/users"{if="$page == 'users'"} class="current"{/if}><i class="glyphicon glyphicon-user glfw"></i>{$lang.admin.users}</a></li>
                    <li><a href="{$admin}/projects"{if="$page == 'projects'"} class="current"{/if}><i class="glyphicon glyphicon-tasks glfw"></i>{$lang.admin.projects}</a></li>
                    <li><a href="{$admin}/plugins"{if="$page == 'plugins'"} class="current"{/if}><i class="glyphicon glyphicon-folder-open glfw"></i>{$lang.admin.plugins}</a></li>
                    <li><hr /></li>
                    {loop="plugin_pages"}
                    <li><a href="{$admin}/plugin/{$key}"{if="$page == $key"} class="current"{/if}><i class="glfw glyphicon{if="!empty($value.glyph)"} glyphicon-{$value.glyph}{/if}"> </i>{$value.title}</a></li>
                    {/loop}
                    {if="count($plugin_pages)>0"}
                    <li><hr /></li>
                    {/if}
                    <li><a href="{$admin}/logout"><i class="glyphicon glyphicon-lock glfw"></i>{$lang.logout}</a></li>
                    {else}
                    <li><a href="{$admin}/login" class="current"><i class="glyphicon glyphicon-wrench glfw"></i>{$lang.login}</a></li>
                    {/if}
                </ul>
            </nav>
        </section>
        
        <header>
            <h3>{$settings.site_name} <span class=" glyphicon glyphicon-wrench"></span> {$lang.admin_panel}</h3>
        </header>
        
        <section class="full-width content">
            {if="isset($status)"}
            {loop="$status"}
            <div class="alert alert-{$value.type}">
                <b>{$lang['status']['type'][$value['type']]}:</b> {$lang['status'][$value[language_key]]}
            </div>
            {/loop}
            {/if}
            {if="$pagedata.plugin != null"}{include="../../plugins/$pagedata[plugin][name]/page.$pagedata.template"}{else}{include="page.$pagedata.template"}{/if}
        </section>
    </body>
</html>