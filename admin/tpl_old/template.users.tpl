            <h2>{$lang.admin.users}</h2>
            {$lang.total}: {$count} {if="$limit - 30 > 0"}<a href="users/{$id - 1}">{$lang.previous_page}</a>{/if}
            {if="$limit < $count"}<a href="users/{$id + 1}">{$lang.next_page}</a>{/if}
            <hr>
            {loop="$users"}
            <div class="entry">
                <img class="left" src="{$settings.base_url}/content/avatar/{if="file_exists(CDIR . '/content/avatar/'. $value.id . '.png')"}{$value.id}{else}default.png{/if}" />
                <p class="right">
                    <b>{$value.displayname}</b><br />
                    <small>{$lang.email}:</small> {$value.email}<br />
                    <small>{$lang.registration_date}:</small> {'d-m-y h:i:s'|date:$value.registered}<br /></br />
                    <small><a href="edit/user/{$value.id}">{$lang.edit}</a> <a href="banuser/{$value.id}">{$lang.ban}</a></small>
                </p>
            </div>
            {/loop}