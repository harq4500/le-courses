<table class="form-table" role="presentation">
    <tbody>
        <tr>
            <th scope="row">
                <label for="course_image_link">
                    Изображение
                </label>
            </th>
            <td>
                <input type="text" id="course_image_link" name="course_image_link" value="<?php echo esc_attr( $course_image_link ); ?>" class="le_input"/>
            </td>
        </tr>
        <tr>
            <th scope="row">
                <label for="course_currency">
                    Валюта
                </label>
            </th>
            <td>
            <input type="text" id="course_currency" name="course_currency" value="<?php echo esc_attr( $course_currency ); ?>" class="le_input"/>
            </td>
        </tr>
        <tr>
            <th scope="row">
                <label for="course_price">
                    Цена
                </label>
            </th>
            <td>
                <input type="text" id="course_price" name="course_price" value="<?php echo esc_attr( $course_price ); ?>"  class="le_input"/>
            </td>
        </tr>
        <tr>
            <th scope="row">
                <label for="course_old_price">
                    Старая цена
                </label>
            </th>
            <td>
                <input type="text" id="course_old_price" name="course_old_price" value="<?php echo esc_attr( $course_old_price ); ?>"  class="le_input"/>
            </td>
        </tr>
        <!-- <tr>
            <th scope="row">
                <label for="course_sale_price">
                    Сниженная цена
                </label>
            </th>
            <td>
                <input type="text" id="course_sale_price" name="course_sale_price" value="<?php echo esc_attr( $course_sale_price ); ?>" class="le_input"/>
            </td>
        </tr>-->
        <tr>
            <th scope="row">
                <label for="course_sale_info">
                    Платежная информация
                </label>
            </th>
            <td>
                <input type="text" id="course_sale_info" name="course_sale_info" value="<?php echo esc_attr( $course_sale_info ); ?>" class="le_input"/>
            </td>
        </tr> 
        <tr>
            <th scope="row">
                <label for="course_payment_by_installment">
                Оплата в рассрочку
                </label>
            </th>
            <td>
                <input type="text" id="course_payment_by_installment" name="course_payment_by_installment" value="<?php echo esc_attr( $course_payment_by_installment ); ?>" class="le_input"/>
            </td>
        </tr>
        <tr>
            <th scope="row">
                <label for="course_start_date">
                    Дата начала
                </label>
            </th>
            <td>
                <input type="text" id="course_start_date" name="course_start_date" value="<?php echo esc_attr( $course_start_date ); ?>" class="le_input"/>
            </td>
        </tr>
        <tr>
            <th scope="row">
                <label for="course_duration">
                Длительность
                </label>
            </th>
            <td>
                <input type="text" id="course_duration" name="course_duration" value="<?php echo esc_attr( $course_duration ); ?>"  class="le_input"/>
            </td>
        </tr>
        <tr>
            <th scope="row">
                <label for="course_link">
                    Ссылка
                </label>
            </th>
            <td>
                <input type="url" id="course_link" name="course_link" value="<?php echo esc_attr( $course_link ); ?>"  class="le_input"/>
            </td>
        </tr>
        <tr>
            <th scope="row">
                <label for="course_external_id">
                    Внешний идентификатор
                </label>
            </th>
            <td>
                <input type="text" id="course_external_id" name="course_external_id" value="<?php echo esc_attr( $course_external_id ); ?>" class="le_input"/>
            </td>
        </tr>
        
    </tbody>
</table>