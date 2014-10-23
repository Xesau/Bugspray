            <h2>{$lang.admin.users}</h2>
            <hr>
            {loop="$users"}
            <div class="user">
                {$value.displayname} (<small>{$value.email}</small>)
                
            </div>
            {/loop}