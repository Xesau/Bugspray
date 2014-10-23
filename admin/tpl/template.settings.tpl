<h2>{$lang.admin.settings}</h2>
<form>
    <table>
        <tr>
            <td>
                {$lang.site_name}
            </td>
            <td>
                <input name="site_name" value="{$settings.site_name}" />
            </td>
        </tr>
        <tr>
            <td>
                {$lang.base_url}
            </td>
            <td>
                <input name="base_url" value="{$settings.base_url}" />
            </td>
        </tr>
        <tr>
            <td>
                {$lang.admin_email}
            </td>
            <td>
                <input name="admin_email" value="{$settings.admin_email}" />
            </td>
        </tr>
        <tr>
            <td>
                {$lang.debug_mode}
            </td>
            <td>
                <select name="debug_mode">
                    <option value="true"{if="$settings.debug_mode == 'true'"} selected{/if}>{$lang.on}</option>
                    <option value="false"{if="$settings.debug_mode == 'false'"} selected{/if}>{$lang.off}</option>
                </select>
            </td>
        </tr>
        <tr>
            <td>
                {$lang.use_labels}?
            </td>
            <td>
                <select name="issue_labels">
                    <option value="true"{if="$settings.issue_labels == 'true'"} selected{/if}>{$lang.use}</option>
                    <option value="false"{if="$settings.issue_labels == 'false'"} selected{/if}>{$lang.dont_use}</option>
                </select>
            </td>
        </tr>
        <tr>
            <td>
                {$lang.use_priority}?
            </td>
            <td>
                <select name="issue_priority">
                    <option value="true"{if="$settings.issue_priority == 'true'"} selected{/if}>{$lang.use}</option>
                    <option value="false"{if="$settings.issue_priority == 'false'"} selected{/if}>{$lang.dont_use}</option>
                </select>
            </td>
        </tr>
        <tr>
            <td>
                {$lang.use_project_version}?
            </td>
            <td>
                <select name="version">
                    <option value="true"{if="$settings.issue_project_version == 'true'"} selected{/if}>{$lang.use}</option>
                    <option value="false"{if="$settings.issue_project_version == 'false'"} selected{/if}>{$lang.dont_use}</option>
                </select>
            </td>
        </tr>
        <tr>
            <td>
                {$lang.use_security}?
            </td>
            <td>
                <select name="security">
                    <option value="true"{if="$settings.issue_security == 'true'"} selected{/if}>{$lang.use}</option>
                    <option value="false"{if="$settings.issue_security == 'false'"} selected{/if}>{$lang.dont_use}</option>
                </select>
            </td>
        </tr>
        <tr>
            <td>
                {$lang.theme}
            </td>
            <td>
                <select name="theme">
                    {loop="$server.available_themes"}
                    <option value="{$value|basename}"{if="$settings.theme == $value"} selected{/if}>{$value|basename}</option>{/loop}
                </select>
            </td>
        </tr>
        <tr>
            <td>
                {$lang.language}
            </td>
            <td>
                <select name="language">
                    {loop="$server.available_languages"}
                    <option value="{$value|basename}    "{if="$settings.theme == $value"} selected{/if}>{$value|filename:'.lang.php'}</option>{/loop}
                </select>
            </td>
        </tr>
        <tr>
            <td></td>
            <td><input type="submit" value="{$lang.save}" /></td>
        </tr>
    </table>
</form>