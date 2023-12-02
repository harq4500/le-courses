<tr class="form-field">
    <th><label for="le_school_url">Ссилка школы</label></th>
    <td>
        <input name="le_school_url" id="le_school_url" type="url" value="<?php echo esc_attr( $le_school_url ) ?>" />
    </td>
</tr>
<tr class="form-field">
    <th>
        <label for="le_school_img">Image Field</label>
    </th>
    <td>
        <?php if( $image = wp_get_attachment_image_url( $le_school_img, 'medium' ) ) : ?>
            <a href="#" class="le_school-upload">
                <img src="<?php echo esc_url( $image ) ?>" />
            </a>
            <a href="#" class="le_school-remove">Remove image</a>
            <input type="hidden" name="le_school_img" value="<?php echo absint( $le_school_img ) ?>">
        <?php else : ?>
            <a href="#" class="button le_school-upload">Upload image</a>
            <a href="#" class="le_school-remove" style="display:none">Remove image</a>
            <input type="hidden" name="le_school_img" value="">
        <?php endif; ?>
    </td>
</tr>