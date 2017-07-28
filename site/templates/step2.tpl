<div class="title_block">
    <div class="title1">Оформление заказа</div>
</div>

<div class="order_block clearfix">
    <div class="order_step nb"><span>1.</span> Контактная информация</div>
    <div class="order_step active"><span>2.</span> Информация о доставке</div>
    <div class="step_info clearfix">
        <?php if (isset($this->message)) { ?><div class="message"><?=$this->message;?></div><?php } ?>
        <form action="index.php?page=Order&action=step2" method="post">
            <div class="clearfix">
                <div class="block40">
                    <h4>Адрес доставки</h4>
                    <label class="info_label">Город:<br><input class="account_input" type="text" name="city" value="<?=$this->user['city'];?>"></label>
                    <label class="info_label">Улица:<br><input class="account_input" type="text" name="street" value="<?=$this->user['street'];?>"></label>
                    <div class="block50 in">
                        <label class="info_label">Дом:<br><input class="account_input" type="text" name="house" value="<?=$this->user['house'];?>"></label>
                    </div>
                    <div class="block50 in">
                        <label class="info_label">Квартира:<br><input class="account_input" type="text" name="room" value="<?=$this->user['room'];?>"></label>
                    </div>
                </div>
                <div class="block20">
                    <h4>Способ доставки</h4>
                    <input type="radio" name="dostavka" value="1" id="rb0" checked><label class="hover" for="rb0">Курьерская доставка с оплатой при получении</label><br>
                    <input type="radio" name="dostavka" value="2" id="rb1"><label class="hover" for="rb1">Почта России с наложенным платежом</label><br>
                    <input type="radio" name="dostavka" value="3" id="rb2"><label class="hover" for="rb2">Доставка через терминалы QIWI Post</label><br>
                </div>
                <div class="block40">
                    <h4>Комментарий к заказу</h4>
                    <label class="info_label">Введите Ваш комментарий:<br><textarea name="comment"><?=$this->comment;?></textarea></label>
                </div>
            </div>
            <input type="submit" class="continue" value="Продолжить">
        </form>
    </div>
    <div class="order_step nb"><span>3.</span> Подтверждение заказа</div>
</div>
