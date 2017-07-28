        <h1><?=$this->title;?></h1>

        <table class="user_info">
            <tr><th>Информация о пользователе</th></tr>
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
                </td>
            </tr>
        </table>

        <table class="user_orders">
            <tr><th colspan="3">История заказаов</th></tr>
            <?php if (isset($this->orders) && $this->orders !== false) foreach($this->orders as $order) { ?>
            <tr><td class="t_order_number"><a class="hover" href="admin.php?page=AdminOrders&action=view&id=<?=$order['id'];?>">№<?=$order['id_pad'];?></a></td>
                <td class="t_order_sum"><?=$order['sum'];?> руб.</td>
                <td class="t_order_time"><?=$order['dt'];?></td>
            </tr>
            <?php } ?>
            <tr class="itog"><td colspan="3"><p><span class="right rub"> руб.</span><span class="right sum"><?=$this->sum;?></span><span class="right itogo">Итоговая<br>сумма заказов</span></p></td></tr>
        </table>
        <?php if ($this->can_delete == true) { ?><a class="del_user hover" href="admin.php?page=AdminUsers&action=delete&id=<?=$this->user['id'];?>">Удалить пользователя</a><?php }?>
