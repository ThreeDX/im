<div class="title_block">
    <div class="title1">Оформление заказа</div>
</div>

<div class="order_block clearfix">
    <div class="order_step"><span>1.</span> Контактная информация</div>
    <div class="order_step nb"><span>2.</span> Информация о доставке</div>
    <div class="order_step active"><span>3.</span> Подтверждение заказа</div>
    <div class="step_info clearfix nb">
        <?php if (isset($this->message)) { ?><div class="message"><?=$this->message;?></div><?php } ?>
        <div class="block100">
            <h4>Состав заказа:</h4>
            <table class="order_table">
                <tr><th>Товар</th><th>Стоимость</th><th>Количество</th><th>Итого</th></tr>
                <?php foreach($this->cart_info as $key => $value) { if ($key == "sum" || $key =="count") continue; ?><tr>
                    <td><?=$value['name'];?><?php if ($value['var']) { ?> (<?=$value['var'];?>)<?php } ?></td>
                    <td class="oprice"><?=$value['price'];?>руб.</td>
                    <td class="ocount"><?=$value['count'];?></td>
                    <td class="osum"><?=$value['price_sum'];?>руб.</td>
                </tr><?php } ?>
                <tr><td colspan="4">
                        <div class="total">Итого:<span id="cart_info_sum"><?=$this->cart_info['sum'];?>руб.</span></div>
                    </td></tr>
            </table>
            <h4>Доставка:</h4>
            <div class="clearfix">
                <div class="block50">
                    <div class="block50">
                        <p><span class="info_label">Контактное лицо (ФИО):</span><br><?=$this->user['name'];?></p>
                        <p><span class="info_label">Контактный телефон:</span><br><?=$this->user['phone'];?></p>
                        <p><span class="info_label">E-mail:</span><br><?=$this->user['email'];?></p>
                    </div>
                    <div class="block50">
                        <p><span class="info_label">Город:</span><br><?=$this->user['city'];?></p>
                        <p><span class="info_label">Улица:</span><br><?=$this->user['street'];?></p>
                        <div><div class="block50 t20"><span class="info_label">Дом:</span><br><?=$this->user['house'];?></div><div class="block50 t20"><span class="info_label">Квартира:</span><br><?=$this->user['room'];?></div></div>
                    </div>
                </div>
                <div class="block50">
                    <div class="block50">
                        <p class="f16"><span class="info_label">Способ доставки:</span><br>
                            <?php if ($this->dostavka == 1) { ?>Курьерская доставка<br>с оплатой при получении<?php } ?>
                            <?php if ($this->dostavka == 2) { ?>Почта России<br>с наложенным платежом<?php } ?>
                            <?php if ($this->dostavka == 3) { ?>Доставка через<br>терминалы QIWI Post<?php } ?>
                        </p>
                        <p class="f16"><span class="info_label">Комментарий к заказу:</span><br><?=$this->comment;?></p>
                    </div>
                </div>
            </div>
            <form action="index.php?page=Order&action=step3" method="post">
                <input type="hidden" name="dostavka" value="<?=$this->dostavka;?>">
                <input type="hidden" name="comment" value="<?=$this->comment;?>">
                <input type="submit" class="end" value="Подтвердить заказ">
            </form>
        </div>
    </div>
</div>
