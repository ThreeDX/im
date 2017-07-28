<div class="title_block">
    <div class="title1">Корзина</div>
</div>

<table class="cart_table">
    <tr><th colspan="2">Товар</th><th>Доступность</th><th>Стоимость</th><th>Количество</th><th colspan="2">Итого</th></tr>
    <?php foreach($this->cart_info as $key => $value) { if ($key == "sum" || $key =="count") continue; ?><tr id='<?=$key;?>'>
        <td><img src="images/items/<?php if($value['image'] !==null) { ?>k<?=$value['image'];?><?php } else { ?>image.jpeg<?php } ?>" alt="image"></td>
        <td><?=$value['name'];?><?php if ($value['var']) { ?> (<?=$value['var'];?>)<?php } ?></td>
        <td class="instock"><?php if ($value['status'] == 1) { ?>есть<?php } else { ?>нет<?php } ?> в наличии</td>
        <td class="cprice"><?=$value['price'];?>руб.</td>
        <td class="count"><div class="counter"><button onclick="ch_count('<?=$key;?>',-1);">-</button><div class="count" id='c<?=$key;?>'><?=$value['count'];?></div><button onclick="ch_count('<?=$key;?>',1);">+</button></div></td>
        <td class="csum" id='s<?=$key;?>'><?=$value['price_sum'];?>руб.</td>
        <td><a href="index.php?page=Cart&action=del&id=<?=$key;?>" target="cart_del"><img src="images/del.png" alt="del"></a></td>
    </tr><?php } ?>
    <tr><td colspan="7">
            <?php if($this->cart_info['count'] > 0) { ?><a href="index.php?page=Order" class="order" id="order_button">Оформить заказ</a><?php } ?>
            <a href="index.php" class="return">Вернуться к покупкам</a>
            <div class="total">Итого:<span id="cart_info_sum"><?=$this->cart_info['sum'];?>руб.</span></div>
    </td></tr>
</table>

<iframe name="cart_del" class="deleted" id="cart_del" onload="ch_cart_del();"></iframe>
<iframe name="cart_fcount" class="deleted" id="cart_fcount" onload="ch_cart_count();"></iframe>