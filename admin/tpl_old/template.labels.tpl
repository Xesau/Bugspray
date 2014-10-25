            <h2>{$lang.admin.labels}</h2>
            <hr> 
            {if="hasPermission( USERID, 'bt_labels_create' )"}<form method="post" action="{$settings.base_url}/admin/newlabel">
                <fieldset class="left">
                    <legend>{$lang.new_label}</legend>
                    <table>
                        <tr>
                            <td>{$lang.label_name}</td>
                            <td><input name="label_name" /></td>
                        </tr>
                        <tr>
                            <td>{$lang.text_color}</td>
                            <td><input name="text_color" class="color" /></td>
                        </tr>
                        <tr>
                            <td>{$lang.background_color}</td>
                            <td><input name="background_color" class="color" /></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td><input type="submit" value="{$lang.add}" /></td>
                        </tr>
                    </table>
                </fieldset>
            </form>
            <form method="post" action="{$settings.base_url}/admin/modifylabel">
                <fieldset class="left">
                    <legend>{$lang.modify_label}: <select name="label" onchange="updateLabel(this)"><option></option>{loop="labels"}<option value="{$key}">{$value.label}</option>{/loop}</select></legend>
                    <table>
                        <tr>
                            <td>{$lang.label_name}</td>
                            <td><input id="modify_name" name="label_name" /></td>
                        </tr>
                        <tr>
                            <td>{$lang.text_color}</td>
                            <td><input id="modify_txt" name="text_color" class="color" /></td>
                        </tr>
                        <tr>
                            <td>{$lang.background_color}</td>
                            <td><input id="modify_bg" name="background_color" class="color" /></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td><input type="submit" value="{$lang.save}" /></td>
                        </tr>
                    </table>
                </fieldset>
            </form>
            <hr class="clear" />{/if}
            {loop="labels"}
            <span class="label" style="background-color: #{$value.bgcolor}; color: #{$value.txtcolor}">{$value.label}</span>{if="hasPermission( USERID, 'bt_labels_remove' )"} <small><a href="deletelabel/{$key}">{$lang.remove}</a></small>{/if}&nbsp;&nbsp;
            {/loop}
            <script type="text/javascript" src="jscolor/jscolor.js"></script>
            <script>
                function updateLabel( elm )
                {
                    var $labels = [];
                    {loop="labels"}$labels[ {$key} ] = [ '{$value.label}', '{$value.txtcolor}', '{$value.bgcolor}' ];
                    {/loop}
                    document.getElementById( 'modify_name' ).value = $labels[ elm.value ][ 0 ];
                    document.getElementById( 'modify_txt' ).value = $labels[ elm.value ][ 1 ];
                    document.getElementById( 'modify_bg' ).value = $labels[ elm.value ][ 2 ];
                    jscolor.color( document.getElementById( 'modify_txt' ), 'color' );
                    jscolor.color( document.getElementById( 'modify_bg' ), 'color' );
                }
            </script>