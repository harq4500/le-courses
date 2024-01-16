<div class="wrap">
    <h1>Импортировать</h1>
    <form action="<?php echo admin_url() .'edit.php?post_type=courses&page=le-courses-import'?>" method="POST">
        <table class="form-table" role="presentation">
            <tbody>
                <tr>
                    <th scope="row">
                        <label for="school">Школа</label>
                    </th>
                    <td>
                       <select class="regular-text <?php echo (isset($errors['school']) && !empty($errors['school']) ? 'error' : '') ?>" id="school" name="school">
                            <option value="">Выбрать школу</option>
                            <?php foreach($schools as $schoolItem): ?>
                                <?php echo $schoolItem->name; ?>
                                <option value="<?php echo $schoolItem->term_id; ?>" <?php echo ($school == $schoolItem->term_id ? 'selected' : ''); ?>><?php echo $schoolItem->name; ?></option>
                            <?php endforeach; ?>
                       </select>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="xmlUrl">XML url</label>
                    </th>
                    <td>
                        <div>
                            <input name="xmlUrl" type="text" id="xmlUrl" value="<?php  echo  $xmlUrl; ?>" class="regular-text <?php echo (isset($errors['xmlUrl']) && !empty($errors['xmlUrl']) ? 'error' : '') ?>" placeholder="XML Url">
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
        <p class="submit">
            <input type="submit" name="add-tolist" id="add-tolist" class="button button-secondary" value="Добавить в список">
            <input type="submit" name="import" id="import" class="button button-primary" value="Импортировать">
        </p>
    </form>
    <h1>Лист для cron job</h1>
    <table class="wp-list-table widefat fixed striped table-view-list tags">
        <thead>
            <tr>
                <th>
                    <span>Школа</span>
                </th>
                <th>
                    <span>Ссылка</span>
                </th>
                <th class="column-links ">
                    <span>Удалить</span>
                </th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($addedUrls as $addedUrlItem): ?>
            <tr >
                <td >
                    <p><?php echo $addedUrlItem->school->name; ?></p>
                </td>
                <td >
                    <p><?php echo $addedUrlItem->url; ?></p>
                </td>
                <td >
                    <form action="<?php echo admin_url() .'edit.php?post_type=courses&page=le-courses-import'?>" method="POST">
                        <p>
                            <button class="button button-danger_le" type="submit" name="delete" value="<?php echo $addedUrlItem->id; ?>">
                                <span class="dashicons dashicons-trash"></span>
                            </button>
                        </p>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        <tfoot>
            <tr>
                <th>
                    <span>Школа</span>
                </th>
                <th>
                    <span>Ссылка</span>
                </th>
                <th class="column-links ">
                    <span>Удалить</span>
                </th>
            </tr>
        </tfoot>
    </table>

    <p>
        <form action="<?php echo admin_url() .'edit.php?post_type=courses&page=le-courses-import'?>" method="POST">
            <p>
                <button class="button button-primary" type="submit" name="import_all" value="import_all">
                    Импортируйте весь список вручную
                </button>
            </p>
        </form>
    </p>
</div>
