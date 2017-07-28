<div class="title_block">
    <div class="title1">Вход</div>
</div>

<div class="item_block login clearfix">
    <?php if (isset($this->message)) { ?><div class="message"><?=$this->message;?></div><?php } ?>
    <div class="block50">
        <h3>Зарегистрированный пользователь</h3>
        <form action="index.php?page=Account&action=login" method="post">
            <label class="info_label">Email адрес:<br><input class="account_input" type="email" name="email" value="<?=$this->email;?>" required></label>
            <label class="info_label">Пароль:<br><input class="account_input" type="password" name="pass" required></label>
            <input type="submit" class="enter" value="Войти">
        </form>
    </div>
    <div class="block50">
        <div class="vert"></div>
        <h3>Новый пользователь</h3>
        <a href="index.php?page=Account&action=registration" class="register">Зарегистрироваться</a>
    </div>
</div>
