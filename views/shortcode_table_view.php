
    <div class="cours-table__head">
        <div class="cours-item__col-name">Курс</div> 
        <div class="cours-item__col-school">Школа </div> 
        <div class="cours-item__price">Цена</div> 
        <div class="cours-item__credit">Платеж в рассрочку</div> 
        <div class="cours-item__duration">Длительность</div> 
        <div class="cours-item__start">Старт курса</div> 
        <div class="cours-item__link">Ссылка на курс</div>
    </div>
    
    <div class="cours-row">
        <?php foreach($posts as $post): ?>
            
        <div class="cours-item">
            <div class="cours-item__col-name cours-item__col">
                <div class="cours-item__name"><?php echo $post->post_title; ?></div>
                <a href="<?php echo  le_redirectUrl($post->ID); ?>" class="cours-item__name-link" target="_blank">Ссылка на курс</a>
            </div> 
            <div class="cours-item__col-school cours-item__col">
                <div class="cours-item__school-img"> 
                    <?php if($post->school->image_url): ?>
                    <img decoding="async" src="<?php echo $post->school->image_url; ?>" alt="img"> 
                    <?php endif; ?>
                    <?php echo $post->school->name; ?> 
                </div>
            </div> 
            <div class="cours-item__price cours-item__col">
                <?php if($post->course_price): ?>
                    
                    <p class="new-price"><?php echo number_format((float) $post->course_price,0,',',' '); ?> ₽.</p>
                <?php else: ?>
                    Бесплатно
                <?php endif; ?>
                <?php if($post->course_old_price): ?>
                    <p class="old-price"><?php echo number_format((float) $post->course_old_price,0,',',' '); ?> ₽.</p>
                <?php endif; ?>

            </div> 
            <div class="cours-item__credit cours-item__col">
                <?php if($post->course_payment_by_installment): ?>
                    <?php echo number_format( (float) $post->course_payment_by_installment,0,',',' '); ?>  ₽./мес
                    <div class="cours-item__credit-dialog"> <span>? <p>Это минимальный платеж за курс в месяц, при покупке в рассрочку.</p> </span> </div>
                <?php else: ?>
                    По запросу
                <?php endif; ?>
            </div> 
            <div class="cours-item__duration cours-item__col">
                 <?php if($post->course_duration): ?>
                <?php echo $post->course_duration; ?> 
                <?php else: ?>
                    Индивидуально
                <?php endif; ?>
            </div> 
            <div class="cours-item__start cours-item__col">
                <?php if($post->course_start_date): ?>
                <?php echo $post->course_start_date; ?> 

                <?php else: ?>
                    <p>В любой момент</p> 
                    <div class="cours-item__credit-dialog"> 
                        <span>? 
                            <p>Данный курс можно начать проходить в любой момент.</p> 
                        </span> 
                    </div>
                <?php endif; ?>
            </div> 
            <div class="cours-item__link ">
                <a href="<?php echo le_redirectUrl($post->ID); ?>" class="cours-item__btn" target="_blank">Ссылка на курс</a>
            </div>
        </div>
        <?php endforeach; ?>
    </div>