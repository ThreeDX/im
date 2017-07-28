        <h1><?=$this->title;?></h1>
        <?php if (isset($this->cat)) { ?>
        <form class="cat_ren" name="cat_rename" action="admin.php?page=AdminCategories&action=rename&id=<?=$this->cat['id'];?>" method="post">
            <label>Текущая категория: <input type="text" class="input_text" name="name" value="<?=$this->cat['name'];?>" required></label>
            <a class="hover" href="#" onclick="submit_form(event,'cat_rename',['name']);">переименовать</a>
        </form>
        <?php } ?>
        <table>
            <tr><th>Название товара</th><th>Стоимость</th><th class="add_item"><a href="admin.php?page=AdminItems&action=add<?php if (isset($this->cat)) { ?>&cat=<?=$this->cat['id'];?><?php } ?>" class="hover">Добавить товар</a></th></tr>
            <?php if (isset($this->items)) foreach($this->items as $c) { ?>
            <tr><td class="t_items_name"><?=$c['name'];?></td><td class="t_items_sum"><?=$c['price'];?> руб.</td><td class="t_view"><a class="hover" href="admin.php?page=AdminItems&action=view&id=<?=$c['id'];?>">просмотр</a></td></tr>
            <?php } ?>
            <tr class="t_cat"><td colspan="3"></td></tr>
        </table>
