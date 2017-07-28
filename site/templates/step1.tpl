<div class="title_block">
    <div class="title1">Оформление заказа</div>
</div>

<div class="order_block clearfix">
    <div class="order_step active"><span>1.</span> Контактная информация</div>
    <div class="step_info clearfix">
        <?php if (isset($this->message)) { ?><div class="message"><?=$this->message;?></div><?php } ?>
        <div class="block40">
            <?php if(!$this->authed) { ?>
            <form action="index.php?page=Account&action=registration&r=Order" method="post">
                <h4>Для новых покупателей</h4>
                <?php } else { ?>
                <form action="index.php?page=Account&action=save&r=Order" method="post">
                    <h4>Контактная информация</h4><?php } ?>
                <label class="info_label">Контактное лицо (ФИО):<br><input class="account_input" type="text" name="name" value="<?=$this->name;?>" required></label>
                <label class="info_label">Контактный телефон:<br><input class="account_input" type="text" name="phone" value="<?=$this->phone;?>" required></label>
                <label class="info_label">E-mail:<br><input class="account_input" type="email" name="email" value="<?=$this->email;?>" required></label>
                <?php if(!$this->authed) { ?>
                <label class="info_label">Пароль:<br><input class="account_input" type="password" name="pass1" value="" required=""></label>
                <label class="info_label">Повторите пароль:<br><input class="account_input" type="password" name="pass2" value="" required></label>
                <input type="submit" value="Зарегистрироваться">
                <?php } else { ?><input type="submit" value="Продолжить"><?php } ?>
            </form>
        </div>
        <?php if(!$this->authed) { ?>
        <div class="block40">
            <form action="index.php?page=Account&action=login&r=Order" method="post">
                <h4>Быстрый вход</h4>
                <label class="info_label">Ваш e-mail:<br><input class="account_input" type="email" name="email" value="<?=$this->email;?>" required></label>
                <label class="info_label">Пароль:<br><input class="account_input" type="password" name="pass" value="" required=""></label>
                <input type="submit" value="Войти">
            </form>
        </div>
        <?php } ?>
    </div>
    <div class="order_step"><span>2.</span> Информация о доставке</div>
    <div class="order_step nb"><span>3.</span> Подтверждение заказа</div>
</div>
