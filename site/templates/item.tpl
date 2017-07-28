    <div class="title_block">
        <div class="title"><?php if ($this->cat > 0) { ?><?=$this->cats[$this->cat]['name'];?><?php } else { ?>Просмотр товара<?php } ?></div>
        <a href="index.php?page=Index&cat=<?=$this->cat;?>">вернуться в каталог</a>
    </div>
<script>var current_image = 0, max_image = <?=count($this->item['images']);?> - 1;</script>
    <div class="item_block clearfix">
        <div class="gallery">
            <div class="item_image"><?php if ($this->item['images'][0]) { ?><img src="images/zoom.png" alt="zoom" class="zoom"><img id="image_big" src="images/items/l<?=$this->item['images'][0];?>" alt="tovar" onclick="document.getElementById('modal_image').className='modal';"><?php } ?></div>
            <?php if(count($this->item['images']) > 0) { ?><div class="scroller">
                <div class="button" onclick="shift_image(-1);"><img src="images/gall.png" alt="left" class="right"></div>
                <?php foreach($this->item['images'] as $k => $image) { ?>
                <div id="image_gal<?=$k;?>" data-image="<?=$image;?>" onclick="set_image(<?=$k;?>)" class="small_image<?php if($k==0) { ?> current<?php } ?>"><img src="images/items/m<?=$image;?>" alt="tovar"></div><?php } ?>
                <div class="button" onclick="shift_image(1);"><img src="images/galr.png" alt="right" class="left"></div>
            </div><?php } ?>
        </div>

        <div class="modal deleted" id="modal_image" onclick="this.className='modal deleted';">
            <img id="image_big_modal" src="images/items/<?=$this->item['images'][0];?>" alt="tovar">
        </div>
        <div class="item_info">
            <h1><?=$this->item['name'];?></h1>
            <div class="item_description"><?=str_replace("\n","<br>",$this->item['description']);?></div>
            <form id="item_form" action="index.php?page=Cart&action=add&id=<?=$this->item['id'];?>" target="cart" method="post">
                <?php if ($this->item['vars']) { ?><div class="vars"><label>Выберите вариант:<select name="vars"><?php foreach($this->item['vars'] as $var) { ?><option><?=$var;?></option><?php } ?></select></label></div><?php } ?>
            </form>
        </div>

        <div class="item_price_block">
            <div class="price_block">
                <p class="old<?php if ($this->item['price_old_raw'] == 0) { ?> hidden<?php } ?>"><?=$this->item['price_old'];?> руб.</p>
                <p class="new"><?=$this->item['price'];?>руб.</p>
                <p class="stock"><?php if ($this->item['status'] == 1) { ?>есть<?php } else { ?>нет<?php } ?> в наличии</p>
                <p class="buy"><a href="#" onclick="document.getElementById('item_form').submit();event.preventDefault();">Купить</a></p>
            </div>
            <p class="item_help"><img src="images/car.png" alt="car">Бесплатная доставка<br><span>по всей Росии</span></p>
            <p class="item_help"><img src="images/support.png" alt="support">Горячая линия<br><span>8 800 000-00-00</span></p>
            <p class="item_help"><img src="images/gift.png" alt="gift">Подарки<br><span>каждому покупателю</span></p>
        </div>
    </div>

    <iframe name="cart" class="deleted" id="cart_add" onload="ch_cart_add();"></iframe>

    <div class="modal deleted" id="modal">
        <div class="modal_message">
            Товар добавлен в корзину!
            <a href="#" class="gray" onclick="document.getElementById('modal').className='modal deleted';event.preventDefault();">Продолжить покупки</a>
            <a href="index.php?page=Cart">Перейти в корзину</a>
        </div>
    </div>
    <div class="items clearfix">
        <div class="items_head">
            <h2>Другие товары<?php if ($this->cat > 0) { ?> из категории «<?=$this->cats[$this->cat]['name'];?>»<?php } ?></h2>
            <div class="arrows"><a href="#" onclick="scroll_left(event,'i3','i4','l3','l4');"><img id="l3" src="images/lg.png" alt="l"></a><a href="#" onclick="scroll_right(event,'i3','i4','l3','l4');"><img id="l4" src="images/rb.png" alt="l"></a></div>
        </div>
        <div class="item_wrapper w1">
            <div id="i3" class="item_container">
                <?php for($i=0;$i<4;$i++) { $item = array_shift($this->data['cat_items']); if ($item) { ?>
                <div class="item"><a href="index.php?page=Item&id=<?=$item['id'];?>">
                        <div class="image"><?php if ($item['images'][1]) { ?><img src="images/items/k<?=$item['images'][1];?>" alt="<?=$item['name'];?>"><?php } else { ?><img src="images/image.jpeg" alt="image"><?php } ?></div>
                        <?php if ($item['bage'] == 1) { ?><div class="label"><img src="images/new.png" alt="new"></div><?php } ?>
                        <?php if ($item['bage'] == 2) { ?><div class="label"><img src="images/hot.png" alt="hot"></div><?php } ?>
                        <?php if ($item['bage'] == 3) { ?><div class="label"><img src="images/sale.png" alt="sale"></div><?php } ?>
                        <p><?=$item['name'];?></p>
                        <p class="price"><?=$item['price'];?><span>руб.</span><?php if ($item['price_old']) { ?><span><br><?=$item['price_old'];?> руб.</span><?php } ?></p>
                    </a></div>
                <?php } } ?>
            </div>
            <div id="i4" class="item_container">
                <?php for($i=0;$i<4;$i++) { $item = array_shift($this->data['cat_items']); if ($item) { ?>
                <div class="item"><a href="index.php?page=Item&id=<?=$item['id'];?>">
                        <div class="image"><?php if ($item['images'][1]) { ?><img src="images/items/k<?=$item['images'][1];?>" alt="<?=$item['name'];?>"><?php } else { ?><img src="images/image.jpeg" alt="image"><?php } ?></div>
                        <?php if ($item['bage'] == 1) { ?><div class="label"><img src="images/new.png" alt="new"></div><?php } ?>
                        <?php if ($item['bage'] == 2) { ?><div class="label"><img src="images/hot.png" alt="hot"></div><?php } ?>
                        <?php if ($item['bage'] == 3) { ?><div class="label"><img src="images/sale.png" alt="sale"></div><?php } ?>
                        <p><?=$item['name'];?></p>
                        <p class="price"><?=$item['price'];?><span>руб.</span><?php if ($item['price_old']) { ?><span><br><?=$item['price_old'];?> руб.</span><?php } ?></p>
                    </a></div>
                <?php } } ?>
            </div>
        </div>
    </div>

