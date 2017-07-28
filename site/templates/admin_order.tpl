        <h1>
            <?=$this->title;?> <span>№<?=$this->order['id_pad'];?></span> <span id="stclass<?=$this->order['id'];?>" class="st<?=$this->order['status'];?>">(<a href="#" class="st<?=$this->order['status'];?>" onclick="cs_show('ch<?=$this->order['id'];?>');" id="stname<?=$this->order['id'];?>"><?=$this->order['status_name'];?></a>)</span>
        </h1>

        <div id="ch<?=$this->order['id'];?>" class="status_change1" onmouseleave="cs_hide(this);">
            <a href="admin.php?page=AdminOrders&action=chstatus&id=<?=$this->order['id'];?>&status=1" class="st1" target="zstatus" onclick="cs_hide(document.getElementById('ch<?=$this->order['id'];?>'));">принят</a>
            <a href="admin.php?page=AdminOrders&action=chstatus&id=<?=$this->order['id'];?>&status=2" class="st2" target="zstatus" onclick="cs_hide(document.getElementById('ch<?=$this->order['id'];?>'));">отгружен</a>
            <a href="admin.php?page=AdminOrders&action=chstatus&id=<?=$this->order['id'];?>&status=3" class="st3" target="zstatus" onclick="cs_hide(document.getElementById('ch<?=$this->order['id'];?>'));">у курьера</a>
            <a href="admin.php?page=AdminOrders&action=chstatus&id=<?=$this->order['id'];?>&status=4" class="st4" target="zstatus" onclick="cs_hide(document.getElementById('ch<?=$this->order['id'];?>'));">доставлен</a>
            <a href="admin.php?page=AdminOrders&action=chstatus&id=<?=$this->order['id'];?>&status=5" class="st5" target="zstatus" onclick="cs_hide(document.getElementById('ch<?=$this->order['id'];?>'));">отменен</a>
        </div>

        <table class="user_orders">
            <tr><th colspan="5">Содержимое заказа</th></tr>
            <?php if (isset($this->order_items) && $this->order_items !== false) foreach($this->order_items as $item) { ?>
            <tr id="zitem<?=$item['id'];?>"><td class="t_item_name"><a href="admin.php?page=AdminItems&action=view&id=<?=$item['item']['id'];?>"><?=$item['item']['name'];?></a><?php if (strlen($item['var'])>0) { ?> <span>(<?=$item['var'];?>)</span><?php } ?></td>
                <td class="t_item_price"><?=$item['item']['price'];?> руб.</td>
                <td class="t_item_count"><input name="count<?=$item['id'];?>" value="<?=$item['count'];?>" disabled></td>
                <td class="t_item_price_sum"><?=$item['item_price_sum'];?> руб.</td>
                <td class="t_item_order_del"><a<?php if ($this->order['status'] >= 4) { ?> class="deleted"<?php } ?> name="del_item" target="zdel_item" href="admin.php?page=AdminOrders&action=item_delete&id=<?=$this->order['id'];?>&item_id=<?=$item['id'];?>">убрать из заказа</a></td>
            </tr>
            <?php } ?>
            <tr class="itog"><td colspan="5"><p><span class="right rub"> руб.</span><span id="order_sum" class="right sum"><?=$this->order['sum'];?></span><span class="right itogo">Итоговая<br>сумма</span></p></td></tr>
        </table>

        <table class="user_info t20">
            <tr><th>Информация о заказе</th></tr>
            <tr>
                <td>
                    <div class="left">
                        <label>Контактное лицо (ФИО):<br><input type="text" name="name" value="<?=$this->user['name'];?>" disabled></label><br>
                        <label>Контактный телефон:<br><input type="text" name="phone" value="<?=$this->user['phone'];?>" disabled></label><br>
                        <label>E-mail:<br><input type="email" name="email" value="<?=$this->user['email'];?>" disabled></label>
                    </div>
                    <div class="left">
                        <label>Город:<br><input class="medium" type="text" name="city" value="<?=$this->user['city'];?>" disabled></label><br>
                        <label>Улица:<br><input class="medium" type="text" name="street" value="<?=$this->user['street'];?>" disabled></label><br>
                        <label class="left">Дом:<br><input type="text" class="small" name="house" value="<?=$this->user['house'];?>" disabled></label>
                        <label class="left">Квартира:<br><input class="small" type="text" name="room" value="<?=$this->user['room'];?>" disabled></label>
                    </div>
                    <div class="left dostavka">
                        <span>Способ доставки:</span><br><span><?=$this->order['dostavka_name'];?></span>
                    </div>
                    <div class="left comment">
                        <label>Комментарий к заказу:<br><textarea class="large" name="comment" disabled><?=$this->order['comment'];?></textarea></label>
                    </div>
                </td>
            </tr>
        </table>

        <a class="del_user hover" href="admin.php?page=AdminOrders&action=delete&id=<?=$this->order['id'];?>">Удалить заказ</a>

        <iframe name="zstatus" class="deleted" id="zstatus" onload="ch_zstatus();"></iframe>
        <iframe name="zdel_item" class="deleted" id="del_zitem" onload="del_zitem();"></iframe>