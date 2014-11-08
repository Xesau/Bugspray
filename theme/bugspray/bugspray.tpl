<!DOCTYPE html>

<html>
    <head>
        <title>{$settings.site_name|strip_tags} &bull; {$pagedata.title}</title>
        <link rel="stylesheet" href="../../admin/tpl/css/bootstrap.min.css" />
        <link rel="stylesheet" href="../../admin/tpl/css/style.css" />
        <script src="http://code.jquery.com/jquery-1.11.0.min.js"></script>
        <script src="../../admin/tpl/js/bootstrap.min.js"></script>
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
                    <li><a href="./"{if="$page == 'home'"} class="current"{/if}><i class="glyphicon glyphicon-home glfw"></i>{$lang.home}</a></li>
                    <li class="haschildren {if="$projectOpen"} open{/if}">
                        <a href="projects"{if="$page == 'projects'"} class="current"{/if}><i class="glyphicon glyphicon-tasks glfw"></i>{$lang.projects}</a>
                        <ul>
                            {loop="$projects"}
                                <li><a {if="$element == $key"}class="current"{/if} href="project/{$key}">{$value.name}</a></li>
                            {/loop}
                        </ul>
                    </li>
                    <hr />
                    {if="LOGGED_IN"}
                    {if="hasPermission(USERID, 'bs_admin')"}
                    <li><a href="admin"><i class="glyphicon glyphicon-cog glfw"></i>{$lang.admin_panel}</a></li>
                    {/if}
                    <li><a href="logout"{if="$page == 'login'"} class="current"{/if}><i class="glyphicon glyphicon-lock glfw"></i>{$lang.logout}</a></li>
                    {else}
                    <li><a href="login"{if="$page == 'login'"} class="current"{/if}><i class="glyphicon glyphicon-user glfw"></i>{$lang.login}</a></li>
                    {/if}
                </ul>
            </nav>
        </section>
        
        <header>
            <h3>{$settings.site_name}</h3>
        </header>
        
        <section class="full-width content">
            {if="isset($status)"}
            <div class="alert alert-{$status.type}">
                <b>{$lang['status']['type'][$status['type']]}:</b> {$lang['status'][$status[language_key]]}
            </div>
            {/if}
            {include="template.$pagedata.template"}
        </section>
    </body>
</html>