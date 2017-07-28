        <h1><?=$this->title;?></h1>

        <table>
            <tr><th>Номер заказа</th><th>Статус</th><th>Сумма</th><th colspan="2">Время заказа</th></tr>
            <?php if (isset($this->orders)) foreach($this->orders as $order) { ?>
            <tr><td class="t_order_number"><a class="hover" href="admin.php?page=AdminOrders&action=view&id=<?=$order['id'];?>">№<?=$order['id_pad'];?></a> от <span><?=$order['owner_data']['email'];?></span></td><td  id="stclass<?=$order['id'];?>" class="t_order_status st<?=$order['status'];?>">
                <div id="ch<?=$order['id'];?>" class="status_change" onmouseleave="cs_hide(this);">
                    <a href="admin.php?page=AdminOrders&action=chstatus&id=<?=$order['id'];?>&status=1" class="st1" target="zstatus" onclick="cs_hide(document.getElementById('ch<?=$order['id'];?>'));">принят</a>
                    <a href="admin.php?page=AdminOrders&action=chstatus&id=<?=$order['id'];?>&status=2" class="st2" target="zstatus" onclick="cs_hide(document.getElementById('ch<?=$order['id'];?>'));">отгружен</a>
                    <a href="admin.php?page=AdminOrders&action=chstatus&id=<?=$order['id'];?>&status=3" class="st3" target="zstatus" onclick="cs_hide(document.getElementById('ch<?=$order['id'];?>'));">у курьера</a>
                    <a href="admin.php?page=AdminOrders&action=chstatus&id=<?=$order['id'];?>&status=4" class="st4" target="zstatus" onclick="cs_hide(document.getElementById('ch<?=$order['id'];?>'));">доставлен</a>
                    <a href="admin.php?page=AdminOrders&action=chstatus&id=<?=$order['id'];?>&status=5" class="st5" target="zstatus" onclick="cs_hide(document.getElementById('ch<?=$order['id'];?>'));">отменен</a>
                </div>
                <a href="#" onclick="cs_show('ch<?=$order['id'];?>');" id="stname<?=$order['id'];?>"><?=$order['status_name'];?></a></td><td class="t_order_sum"><?=$order['sum'];?> руб.</td><td class="t_order_time"><?=$order['dt'];?></td><td class="t_view"><a class="hover" href="admin.php?page=AdminOrders&action=view&id=<?=$order['id'];?>">просмотр</a></td></tr>
            <?php } ?>
            <tr><td colspan="5"></td></tr>
        </table>

        <iframe name="zstatus"  class="deleted" id="zstatus" onload="ch_zstatus();"></iframe>
