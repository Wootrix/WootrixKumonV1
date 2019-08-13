<div id="form_count_<?php echo $i; ?>">

    <?php if($i > 1): ?>
        <button style="float:right" onclick="del_form('<?php echo $i; ?>')" class="remove_field glyphicon delete-bttn"></button>
    <?php endif; ?>

    <div>

        <div class="row">
            <div class="form-group">
                <label>Message Type</label>
                <select required name="msgtype[<?php echo $i; ?>]" id="fld_msgtyp_<?php echo $i; ?>"
                        class="form-control select-down-arrow fld_msgtype">
                    <option value="">Please Select...</option>
                    <option value="message">Message</option>
                    <option value="closemagazine">Magazine</option>
                </select>
            </div>
            <div id="div_msgtyp_<?php echo $i; ?>"></div>
        </div>

    </div>

    <div class="row" id="div_cmtyp_<?php echo $i; ?>" style="display: none;"></div>

    <div class="row">

        <div class="input-box" style="float: left; width: 50%;">

            <label class="full-width-label">Tipo de usuário</label>

            <div class="selectAll">
                <input type="checkbox" name='selectall' id='selectall' onClick="toggleGroup(this, '<?php echo $i; ?>')"/>
                Select All
            </div>

            <div class="multiselect-box clearfix fullbox selectCategory">
                <?php
                if (!empty($groups)) {
                    foreach ($groups as $value) {
                        $id = $value['id'];
                        $magazine_name = $value['name'];
                        echo '<div class="cbox"><input type="checkbox"  class="catagory_class" id="group[]" name="group[' . $i . '][]" value="' . $id . '" />' . $magazine_name . '</div>';
                    }
                }
                ?>
            </div>

            <label for="catagory" style="display:none;" generated="true"
                   class="error category_class_error"><?php echo $this->lang->line("Please_select_magazine") ?>
                .</label>

        </div>

        <div class="input-box" style="float: left;">

            <label class="full-width-label">Localidade</label>

            <div class="selectAll">
                <input type="checkbox" name='selectall' id='selectall' onClick="toggleLocation(this, '<?php echo $i; ?>')"/>
                Select All
            </div>

            <div class="multiselect-box clearfix fullbox selectCategory">
                <?php
                if (!empty($locations)) {
                    foreach ($locations as $value) {
                        $id = $value['id'];
                        $magazine_name = $value['city'];
                        echo '<div class="cbox"><input type="checkbox"  class="catagory_class" id="location[]" name="location[' . $i . '][]" value="' . $id . '" />' . $magazine_name . '</div>';
                    }
                }
                ?>
            </div>

            <label for="location" style="display:none;" generated="true"
                   class="error category_class_error">Por favor, selecione uma localidade.</label>

        </div>

    </div>

    <div class="row">

        <div class="input-box" style="float: left; width: 50%;">

            <label class="full-width-label">Matéria</label>

            <div class="selectAll">
                <input type="checkbox" name='selectall' id='selectall'
                       onClick="toggleDiscipline(this, '<?php echo $i; ?>')"/> Select All
            </div>

            <div class="multiselect-box clearfix fullbox selectCategory">
                <?php
                if (!empty($disciplines)) {
                    foreach ($disciplines as $value) {
                        $id = $value['id'];
                        $magazine_name = $value['name'];
                        echo '<div class="cbox"><input type="checkbox"  class="catagory_class" id="discipline[]" name="discipline[' . $i . '][]" value="' . $id . '" />' . $magazine_name . '</div>';
                    }
                }
                ?>
            </div>

            <label for="catagory" style="display:none;" generated="true"
                   class="error category_class_error">Por favor, selecione uma matéria.</label>

        </div>

        <div class="input-box" style="float: left;">

            <label class="full-width-label">Filial</label>

            <div class="selectAll">
                <input type="checkbox" name='selectall' id='selectall' onClick="toggleBranch(this, '<?php echo $i; ?>')"/>
                Select All
            </div>

            <div class="multiselect-box clearfix fullbox selectCategory">
                <?php
                if (!empty($branches)) {
                    foreach ($branches as $value) {
                        $id = $value['branch'];
                        $magazine_name = $value['branch'];
                        echo '<div class="cbox"><input type="checkbox"  class="catagory_class" id="branch[]" name="branch[' . $i . '][]" value="' . $id . '" />' . $magazine_name . '</div>';
                    }
                }
                ?>
            </div>

            <label for="catagory" style="display:none;" generated="true"
                   class="error category_class_error">Por favor, selecione uma matéria.</label>

        </div>

    </div>

    <div class="row" id="div_message_0">
        <div class="full-width-form-group form-group-text-area">
            <label>Message</label>
            <textarea row="10" width="100%" name="message[<?php echo $i; ?>]" id="fld_msg_<?php echo $i; ?>"
                      class="form-control"></textarea>
        </div>
    </div>

</div>