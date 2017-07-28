        <h1><?=$this->title;?></h1>

        <table>
            <tr><th>IP</th><th>Пользователь</th><th>Дата</th><th>Описание</th></tr>
            <?php if (isset($this->logs)) foreach($this->logs as $log) { ?>
            <tr><td class="t_user_name"><?=$log['ip'];?></td><td class="t_user_name"><?php if(is_array($log['user'])) echo $log['user']['email']; ?></td><td class="t_order_time"><?=$log['td'];?></td><td class="t_user_phone"><?=$log['desc'];?></td></tr>
            <?php } ?>
            <tr><td colspan="4"></td></tr>
        </table>
