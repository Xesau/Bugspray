            <h2>{$lang.admin.projects}</h2>
            {$lang.total}: {$count} {if="$limit - 30 > 0"}<a href="projects/{$id - 1}">{$lang.previous_page}</a>{/if}
            {if="$limit < $count"}<a href="projects/{$id + 1}">{$lang.next_page}</a>{/if}
            <hr>
            {loop="$projects"}
            <div class="entry">
                <img class="left" src="{$settings.base_url}/content/project_imgs/{if="file_exists(CDIR . '/content/project_imgs/'. $value.id . '.png')"}{$value.id}{else}default.png{/if}" />
                <p class="right">
                    <b>{$value.name}</b><br />
                    {$value.description}<br /><br />
                    <small><a href="edit/user/{$value.id}">{$lang.edit}</a> <a href="removeproject/{$value.id}">{$lang.remove}</a></small>
                </p>
            </div>
            {/loop}