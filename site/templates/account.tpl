<div class="title_block">
    <div class="title1">Личный кабинет</div>
</div>

<div class="item_block clearfix">
    <div class="block50">
        <h3>Ваши данные</h3>
        <form action="index.php?page=Account&action=save" method="post">
            <label class="info_label">Контактное лицо (ФИО):<br><input class="account_input" type="text" name="name" value="<?=$this->user['name'];?>" required></label>
            <label class="info_label">Контактный телефон:<br><input class="account_input" type="text" name="phone" value="<?=$this->user['phone'];?>" required></label>
            <label class="info_label">Email адрес:<br><input class="account_input" type="email" name="email" value="<?=$this->user['email'];?>" required></label>
            <h3>Адрес доставки</h3>
            <label class="info_label">Город:<br><input class="account_input" type="text" name="city" value="<?=$this->user['city'];?>"></label>
            <label class="info_label">Улица:<br><input class="account_input" type="text" name="street" value="<?=$this->user['street'];?>"></label>
            <div class="block50 in">
                <label class="info_label">Дом:<br><input class="account_input" type="text" name="house" value="<?=$this->user['house'];?>"></label>
            </div>
            <div class="block50 in">
                <label class="info_label">Квартира:<br><input class="account_input" type="text" name="room" value="<?=$this->user['room'];?>"></label>
            </div>
            <h3>Изменение пароля</h3>
            <label class="info_label">Введите новый пароль:<br><input class="account_input" type="password" name="pass1" value=""></label>
            <label class="info_label">Повторите новый пароль:<br><input class="account_input" type="password" name="pass2" value=""></label>
            <input type="submit" value="Сохранить">
        </form>
    </div>
    <div class="block50">
        <div class="vert"></div>
        <h3>Ваши заказы</h3>
        <?php if (isset($this->orders) && $this->orders !== false) foreach($this->orders as $order) { ?>
        <div class="account_order">
            <p class="order_short">
                <span class="os_number">№<?=$order['id_pad'];?></span><br>
                <span class="os_sum">(<?=$order['sum'];?>руб.)</span><br>
                <span class="os_date"><?=$order['dt'];?></span>
            </p>
            <?=$order['status_name'];?>
        </div>
        <?php } ?>
    </div>
</div>
