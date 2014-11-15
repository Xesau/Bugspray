<!DOCTYPE html>

<html>
    <head>
        <title>{$settings.site_name|strip_tags} &bull; {$pagedata.title}</title>
        <link rel="stylesheet" href="{$settings.base_url}/admin/tpl/css/bootstrap.min.css" />
        <link rel="stylesheet" href="{$settings.base_url}/admin/tpl/css/style.css" />
        <script src="http://code.jquery.com/jquery-1.11.0.min.js"></script>
        <script src="{$settings.base_url}/admin/tpl/js/bootstrap.min.js"></script>
        {loop="$pagedata.js"}
        <script src="{$value}"></script>
        {/loop}
        {loop="$pagedata.css"}
        <link rel="stylesheet" href="{$value}" />
        {/loop}
    </head>
    
    <body>
        <section class="sidebar">
            <nav>
                <ul class="no-spacing">
                    <li><a href="{$settings.base_url}"{if="$page == 'home'"} class="current"{/if}><i class="glyphicon glyphicon-home glfw"></i>{$lang.home}</a></li>
                    <li class="haschildren {if="$projectOpen"} open{/if}">
                        <a href="{$settings.base_url}/projects"{if="$page == 'projects'"} class="current"{/if}><i class="glyphicon glyphicon-tasks glfw"></i>{$lang.projects}</a>
                        <ul>
                            {loop="$projects"}
                                <li><a {if="$element == $key"}class="current"{/if} href="{$settings.base_url}/project/{$key}">{$value.name}</a></li>
                            {/loop}
                        </ul>
                    </li>
                    {if="LOGGED_IN"}
                    <li><a href="profile"{if="$page == 'profile'"}class="selected"{/if}><i class="glyphicon glyphicon-user glfw"></i>{$lang.profile}</a></li>
                    <hr />
                    {if="hasPermission( USERID, 'bs_admin' )"}
                    <li><a href="{$settings.base_url}/admin"><i class="glyphicon glyphicon-cog glfw"></i>{$lang.admin_panel}</a></li>
                    {/if}
                    <li><a href="{$settings.base_url}/logout"{if="$page == 'login'"} class="current"{/if}><i class="glyphicon glyphicon-lock glfw"></i>{$lang.logout}</a></li>
                    {else}
                    <hr />
                    <li><a href="{$settings.base_url}/login"{if="$page == 'login'"} class="current"{/if}><i class="glyphicon glyphicon-user glfw"></i>{$lang.login}</a></li>
                    {/if}
                </ul>
            </nav>
        </section>
        
        <header>
            <h3>{$settings.site_name}</h3>
        </header>
        
        <section class="full-width content">
            {if="isset($status)"}
            {loop="$status"}
            <div class="alert alert-{$value.type}">
                <b>{$lang['status']['type'][$value['type']]}:</b> {$lang['status'][$value[language_key]]}
            </div>
            {/loop}
            {/if}
            {include="template.$pagedata.template"}
        </section>
    </body>
</html>