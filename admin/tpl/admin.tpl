<!DOCTYPE html>

<html>
    <head>
        <title>{$settings.site_name|strip_tags} &bull; {$pagedata.title}</title>
        <link rel="stylesheet" href="css/bootstrap.min.css" />
        <link rel="stylesheet" href="css/style.css?a" />
        <script src="http://code.jquery.com/jquery-1.11.0.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
    </head>
    
    <body>
        <section class="sidebar">
            <nav>
                <ul class="no-spacing">
                    {if="LOGGED_IN && hasPermission( USERID, 'bs_admin' )"}
                    <li><a href="./"{if="$page == 'home'"} class="current"{/if}><i class="glyphicon glyphicon-home glfw"></i>{$lang.admin.home}</a></li>
                    <li><a href="settings"{if="$page == 'settings'"} class="current"{/if}><i class="glyphicon glyphicon-cog glfw"></i>{$lang.admin.settings}</a></li>
                    <li><a href="labels"{if="$page == 'labels'"} class="current"{/if}><i class="glyphicon glyphicon-tags glfw"></i>{$lang.admin.labels}</a></li>
                    <li><a href="users"{if="$page == 'users'"} class="current"{/if}><i class="glyphicon glyphicon-user glfw"></i>{$lang.admin.users}</a></li>
                    <li><a href="projects"{if="$page == 'projects'"} class="current"{/if}><i class="glyphicon glyphicon-tasks glfw"></i>{$lang.admin.projects}</a></li>
                    <li><a href="plugins"{if="$page == 'plugins'"} class="current"{/if}><i class="glyphicon glyphicon-folder-open glfw"></i>{$lang.admin.plugins}</a></li>
                    <li><hr /></li>
                    {loop="plugin_pages"}
                    <li><a href="plugin/{$key}"{if="$page == $key"} class="current"{/if}><i class="glfw glyphicon{if="!empty($value.glyph)"} glyphicon-{$value.glyph}{/if}"> </i>{$value.title}</a></li>
                    {/loop}
                    {if="count($plugin_pages)>0"}
                    <li><hr /></li>
                    {/if}
                    <li><a href="logout"><i class="glyphicon glyphicon-lock glfw"></i>{$lang.logout}</a></li>
                    {else}
                    <li><a href="login" class="current"><i class="glyphicon glyphicon-wrench glfw"></i>{$lang.login}</a></li>
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