            <h2>{$lang.admin.labels}</h2>
            <hr> 
            <form method="post" action="newlabel">
                <fieldset>
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

            <hr>
            {loop="labels"}
            <span class="label" style="background-color: #{$value.bgcolor}; color: #{$value.txtcolor}">{$key}</span> <small><a href="deletelabel/{$key}">{$lang.remove}</a></small>&nbsp;&nbsp;
            {/loop}
            <script type="text/javascript" src="jscolor/jscolor.js"></script>