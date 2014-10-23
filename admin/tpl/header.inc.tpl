<!DOCTYPE html>

<html>
    <head>
        <title>{$settings.site_name|strip_tags} &bull; {$pagedata.title}</title>
        <link rel="stylesheet" href="style.css">
        <link rel="stylesheet" href="font-awesome.min.css" />
    </head>
    <body>
        <section id="sidebar">
            <h2>{$settings.site_name}</h2>
            <nav>
                <ul>
                    {if="$loggedIn"}<li{if="$page=='home'"} class="selected"{/if}><a href="./">{$lang.admin.home} <i class="right fa fa-arrow-right"></i></a></li>
                    <li{if="$page=='settings'"} class="selected"{/if}><a href="settings">{$lang.admin.settings} <i class="right fa fa-arrow-right"></i></a></li>
                    <li{if="$page=='projects'"} class="selected"{/if}><a href="projects">{$lang.admin.projects} <i class="right fa fa-arrow-right"></i></a></li>
                    <li{if="$page=='users'"} class="selected"{/if}><a href="users"> {$lang.admin.users}<i class="right fa fa-arrow-right"></i></a></li>
                    {else}<li class="selected"><a href="login">{$lang.login} <i class="right fa fa-arrow-right"></i></a></li>{/if}
                </ul>
            </nav>
        </section>
        <section id="content">