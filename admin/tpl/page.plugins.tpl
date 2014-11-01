<h3 class="no-spacing">{$lang.installed_plugins}</h3>   
<hr />
{loop="plugins"}
<div class="entry">
    <b>{$value->getName()}</b>
    {$value->getVersion()}
    ({if="$value->getWebsite() == ''"}{$value->getAuthor}{else}<a href="{$value->getWebsite()}">{$value->getAuthor()}</a>{/if})
    <br /><br />
    <small><a href="disable_plugin/{$value->getName()}">{$lang.disable_plugin}</a></small></div>
{/loop}
{if="empty($plugins)"}{$lang.no_plugins}{/if}
<hr />
<h3>{$lang.disabled_plugins}</h3>
{loop="disabledPlugins"}
<div class="entry">
    <b>{$value->getName()}</b> {$value->getVersion()} ({if="$value->getWebsite() == ''"}{$value->getAuthor}{else}<a href="{$value->getWebsite()}">{$value->getAuthor()}</a>{/if})
    <br /><br />
    <small><a href="enable_plugin/{$value->getName()}">{$lang.enable_plugin}</a></small>
</div>
{/loop}
{if="empty( $disabledPlugins )"}{$lang.no_plugins_disabled}{/if}
<hr />
<h3>{$lang.find_new_plugins}</h3>
<a href="http://GET_PLUGINS.org/">GET PLUGINS</a>
<hr />
<h3>{$lang.how_to_install_plugins}</h3>
{$lang.plugin_install_desc}