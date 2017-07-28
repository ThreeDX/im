        <h1><?=$this->title;?></h1>

        <table>
            <tr><th>Название категории</th><th>Количество товаров</th><th colspan="2"></th></tr>
            <?php if (isset($this->cat)) foreach($this->cat as $c) { ?>
            <tr><td class="t_cat_name"><img src="images/admin/papka.png" alt="folder_icon"> <?=$c['name'];?></td><td class="t_cat_count"><?=$c['count'];?></td><td class="t_delete"><?php if ($c['count'] == 0) { ?><a class="hover" href="admin.php?page=AdminCategories&action=delete&id=<?=$c['id'];?>">удалить</a><?php } ?></td><td class="t_view"><a class="hover" href="admin.php?page=AdminItems&cat=<?=$c['id'];?>">просмотр</a></td></tr>
            <?php } ?>
            <tr class="t_cat"><td colspan="4"></td></tr>
        </table>
        <form class="cat_add" name="cat_add" action="admin.php?page=AdminCategories&action=add" method="post">
            <label>Добавить категорию: <input type="text" class="input_text" name="name" placeholder="название категории" required></label>
            <br><a class="hover" href="#" onclick="submit_form(event,'cat_add',['name']);">добавить категорию</a>
        </form>
