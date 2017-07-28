<!DOCTYPE html>
<html>
<head lang="ru">
    <meta charset="UTF-8">
	<meta name="viewport" content="width=1170, initial-scale=1">
    <link href="css/fontsf.css" rel="stylesheet">
    <link href="css/stylef.css" rel="stylesheet">
    <script src="js/front.js"></script>
    <title><?=$this->title;?></title>
</head>
<body <?php if (isset($this->content) && $this->content == "home") { ?>class="bg" <?php } ?>id="top">
<div class="container">
    <div class="header clearfix">
        <div class="logo"><a href="index.php"></a></div>
        <div class="menu">
            <div class="nav_cat">
                <ul><?php foreach($this->cats as $cat) { ?>
                    <li<?php if (isset($this->cat) && $this->cat == $cat['id']) { ?> class="current"<?php } ?>><a href="index.php?page=Index&cat=<?=$cat['id'];?>"><?=$cat['name'];?></a></li>
                    <?php } ?>
                </ul>
            </div>
            <div class="nav_auth">

                <img src="images/user.png" alt="user">
                <a href="index.php?page=Account" class="a_auth"><?php if(isset($this->role)) { ?>Профиль<?php } else { ?>Войти<?php } ?></a>
                <?php if(isset($this->role)) { ?><a href="index.php?page=Account&action=logout" class="a_reg">Выйти</a><?php } else { ?><a href="index.php?page=Account&action=registration" class="a_reg">Регистрация</a><?php } ?>
            </div>
        </div>
        <a href="index.php?page=Cart" class="cart">
            <div class="cart_sum">
                <span class="cart_num" id="cart_sum"><?=$this->cart['sum'];?></span> руб.<br><span class="cart_item_num">предметов: <span id="cart_count"><?=$this->cart['count'];?></span></span>
            </div>
            <img src="images/cart.png" alt="cart">
        </a>
        <?php if(isset($this->role) && $this->role > 0) { ?>
        <a href="admin.php" class="cart admin">Админка</a>
        <?php } ?>
    </div>

    <?php
         if (isset($this->content))
            include_once($this->content.".tpl");
    ?>

    <div class="footer clearfix">
        <p>Шаблон для экзаменационного задания.<br>
            Разработан специально для «Всероссийской Школы Программирования»<br>
            <a href="http://bedev.ru/">http://bedev.ru/</a></p>
        <a class="up_top" href="#top">Наверх <img src="images/up.png" alt="up"></a>
    </div>

</div>

</body>
</html>