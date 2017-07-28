<div class="title_block">
    <div class="title1">Регистрация</div>
</div>

<div class="item_block login clearfix">
    <?php if (isset($this->message)) { ?><div class="message"><?=$this->message;?></div><?php } ?>
    <form action="index.php?page=Account&action=registration" method="post">
    <div class="block50">
        <label class="info_label">Контактное лицо (ФИО):<br><input class="account_input" type="text" name="name" value="<?=$this->name;?>" required></label>
        <label class="info_label">Email адрес:<br><input class="account_input" type="email" name="email" value="<?=$this->email;?>" required></label>
        <input type="submit" value="Зарегистрироваться">
    </div>
    <div class="block50">
        <label class="info_label">Пароль:<br><input class="account_input" type="password" name="pass1" value="" required=""></label>
        <label class="info_label">Повторите пароль:<br><input class="account_input" type="password" name="pass2" value="" required></label>
    </div>
    </form>
</div>
