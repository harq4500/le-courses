<table class="cours-table">
    <thead  class="cours-table__head"> 
        <tr  class="cours-table__head">
            <th class="cours-item__col-name">Курс</th> 
            <th class="cours-item__col-school">Школа </th> 
            <th class="cours-item__price">Цена</th> 
            <th class="cours-item__credit">Платеж в рассрочку</th> 
            <th class="cours-item__duration">Длительность</th> 
            <th class="cours-item__start">Старт курса</th> 
            <th class="cours-item__link">Ссылка на курс</th>
        </tr>
    </thead>
    <tbody class="cours-row">
        <?php foreach($posts as $post): ?>
            
        <tr class="cours-item">
            <td class="cours-item__col-name cours-item__col">
                <div class="cours-item__name"><?php echo $post->post_title; ?></div>
                <a href="<?php echo $post->course_link; ?>" class="cours-item__name-link">Ссылка на курс</a>
            </td> 
            <td class="cours-item__col-school cours-item__col">
                <div class="cours-item__school-img"> 
                    <img decoding="async" src="<?php echo $post->school->image_url; ?>" alt="img"> 
                    <?php echo $post->school->name; ?> 
                </div>
            </td> 
            <td class="cours-item__price cours-item__col">
                <?php echo $post->course_price; ?> 
            </td> 
            <td class="cours-item__credit cours-item__col">
                <?php echo $post->course_sale_price; ?> 
                <?php if($post->course_payment_info): ?>
                    <div class="cours-item__credit-dialog"> 
                        <span>? <p><?php echo $post->course_payment_info; ?></p> </span> 
                    </div>
                <?php endif; ?>
            </td> 
            <td class="cours-item__duration cours-item__col">
                 <?php if($post->course_duration): ?>
                <?php echo $post->course_duration; ?> мес.
                <?php endif; ?>
            </td> 
            <td class="cours-item__start cours-item__col">
                <?php echo $post->course_start_date; ?> 
            </td> 
            <td class="cours-item__link ">
                <a href="<?php echo $post->course_link; ?>" class="cours-item__btn">Ссылка на курс</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>