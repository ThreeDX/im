        <h1><?=$this->title;?></h1>

        <table>
            <tr><th>Имя</th><th>E-Mail</th><th colspan="2">Телефон</th></tr>
            <?php if (isset($this->users)) foreach($this->users as $user) { ?>
            <tr><td class="t_user_name"><?=$user['name'];?></td><td class="t_user_email"><?=$user['email'];?></td><td class="t_user_phone"><?=$user['phone'];?></td><td class="t_view"><a class="hover" href="admin.php?page=AdminUsers&action=view&id=<?=$user['id'];?>">просмотр</a></td></tr>
            <?php } ?>
            <tr><td colspan="4"></td></tr>
        </table>
