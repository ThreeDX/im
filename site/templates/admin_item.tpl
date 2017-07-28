        <h1><?=$this->title;?></h1>

        <form action="admin.php?page=AdminItems&action=save&id=<?=$this->item['id'];?>" method="post" enctype="multipart/form-data">
        <table class="item_info">
            <tr><th>Информация о товаре</th></tr>
            <tr>
                <td>
                    <div class="left">
                        <label>Категория:<br><select name="cat">
                                <option value="0"<?php if ($this->item['cat'] == 0) { ?> selected<?php } ?>>Не выбрана</option>
                                <?php foreach ($this->cats as $cat) { ?>
                                <option value="<?=$cat['id'];?>"<?php if ($this->item['cat'] == $cat['id']) { ?> selected<?php } ?>><?=$cat['name'];?></option>
                                <?php } ?>
                            </select></label><br>
                        <label>Название товара:<br><input type="text" name="name" required value="<?=$this->item['name'];?>"></label><br>
                        <label>Цена товара:<br><input type="text" name="price" required value="<?=$this->item['price_raw'];?>"></label><br>
                        <label>Старая цена товара:<br><input type="text" name="price_old" value="<?=$this->item['price_old_raw'];?>"></label><br>
                        <label>Описание товара:<br><textarea required name="description"><?=$this->item['description'];?></textarea></label>
                    </div>
                    <div class="left">
                        <span class="label">Бейджик:<br>
                            <input type="radio" name="bage" value="0" id="rb0"<?php if ($this->item['bage'] == 0) { ?> checked<?php } ?>><label class="hover" for="rb0">Отсутствует</label><br>
                            <input type="radio" name="bage" value="1" id="rb1"<?php if ($this->item['bage'] == 1) { ?> checked<?php } ?>><label class="hover" for="rb1">NEW</label><br>
                            <input type="radio" name="bage" value="2" id="rb2"<?php if ($this->item['bage'] == 2) { ?> checked<?php } ?>><label class="hover" for="rb2">HOT</label><br>
                            <input type="radio" name="bage" value="3" id="rb3"<?php if ($this->item['bage'] == 3) { ?> checked<?php } ?>><label class="hover" for="rb3">SALE</label><br>
                        </span>
                        <span class="label t20">Статус:<br>
                            <input type="radio" name="status" value="1" id="rs1"<?php if ($this->item['status'] == 1) { ?> checked<?php } ?>><label class="hover" for="rs1">В наличии</label><br>
                            <input type="radio" name="status" value="2" id="rs2"<?php if ($this->item['status'] == 2) { ?> checked<?php } ?>><label class="hover" for="rs2">Отсутствует</label><br>
                            <input type="radio" name="status" value="3" id="rs3"<?php if ($this->item['status'] == 3) { ?> checked<?php } ?>><label class="hover" for="rs3">Удален</label><br>
                        </span>
                    </div>
                </td>
            </tr>
        </table>

        <table>
            <tr><th>фотографии товара</th></tr>
            <tr>
                <td>
                    <?php foreach($this->item['images'] as $key => $value) { ?>
                    <div class="photo_item">
                        <?php if ($value) { ?>
                        <div class="photo_image image" id="photo_img<?=$key;?>"><img src="./images/items/m<?=$value;?>" alt="m<?=$value;?>"></div>
                        <?php } else { ?>
                        <div class="photo_image" id="photo_img<?=$key;?>">не загружено</div>
                        <?php } ?>
                        <input type="file" name="img<?=$key;?>" class="deleted" id="file_img<?=$key;?>" onchange="photo_change(<?=$key;?>);">
                        <input type="hidden" name="img_deleted<?=$key;?>" value="0" id="img_deleted<?=$key;?>">
                        <label for="file_img<?=$key;?>" class="photo_upload<?php if ($value) { ?> change<?php } ?>" id="label_img<?=$key;?>"><?php if (!$value) { ?>Загрузить<?php } else { ?>Изменить<?php } ?></label><br>
                        <a id="del_img<?=$key;?>" href="#" class="photo_delete<?php if (!$value) { ?> deleted<?php } ?>" onclick="photo_del(event,<?=$key;?>);">Удалить</a>
                    </div>
                    <?php } ?>
                </td>
            </tr>
        </table>

        <table class="user_info t20">
            <tr><th>Вариации товара</th><th class="add_var"><a href="#" class="hover" onclick="add_var(event);">Добавить вариацию</a></th></tr>
            <tr>
                <td colspan="2" id="item_vars">
                    <?php if ($this->item['vars']) foreach($this->item['vars'] as $var) { ?>
                    <div><input name='vars[]' type='text' placeholder='Вариация' value="<?=$var;?>" required><a href='#' class='hover' onclick='remove_var(event,this.parentNode);'>Удалить</a></div>
                    <?php } ?>
                </td>
            </tr>
        </table>
        <input type="submit" value="submit" class="deleted" id="submit">
        <label for="submit" class="submit_item hover">Сохранить изменения</label>
        </form>