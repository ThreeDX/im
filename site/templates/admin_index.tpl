<!DOCTYPE html>
<html>
<head lang="ru">
    <meta charset="UTF-8">
	<meta name="viewport" content="width=970, initial-scale=1">
    <link href="css/fontsf.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <script src="js/admin.js"></script>
    <title><?=$this->title;?></title>
</head>
<body onload="redraw_left_block();">
<div class="wrapper">
<div class="container">
    <div id="left_container" class="left_container">
        <div class="logo"></div>
        <div class="nav">
            <ul>
                <li<?php if ($this->current == "orders") echo " class=\"current\""; ?>><a href="admin.php?page=AdminOrders" id="m1">Заказы</a></li>
                <li<?php if ($this->current == "users") echo " class=\"current\""; ?>><a href="admin.php?page=AdminUsers" id="m2">Пользователи</a></li>
                <li<?php if ($this->current == "items") echo " class=\"current\""; ?>><a href="admin.php?page=AdminItems" id="m3">Товары</a></li>
                <li<?php if ($this->current == "cat") echo " class=\"current\""; ?>><a href="admin.php?page=AdminCategories" id="m4">Категории</a></li>
                <li<?php if ($this->current == "logs") echo " class=\"current\""; ?>><a href="admin.php?page=AdminLogs" id="m5">Логи</a></li>
            </ul>
        </div>
        <div class="footer">
            <p><?=SesHelper::instance()->get_user_email();?><br>
                <a href="index.php">выйти</a>
            </p>

        </div>
    </div>
    <div class="main">
        <?php
         if (isset($this->content))
            include_once($this->content.".tpl");
        ?>
    </div>
</div>
</div>
</body>
</html>