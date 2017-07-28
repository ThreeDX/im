    <div class="slider">
        <p class="name">Название<br><span>Промо-товара</span></p>
        <p class="description">Описание промо-товара</p>
        <a href="#">Посмотреть +</a>
    </div>

    <div class="items clearfix">
        <div class="items_head">
            <h2>Новые товары</h2>
            <div class="arrows"><a href="#" onclick="scroll_left(event,'i1','i2','l1','l2');"><img id="l1" src="images/lg.png" alt="l"></a><a href="#" onclick="scroll_right(event,'i1','i2','l1','l2');"><img id="l2" src="images/rb.png" alt="l"></a></div>
        </div>
        <div class="item_wrapper w2">
            <div id="i1" class="item_container">
                <?php for($i=0;$i<8;$i++) { $item = array_shift($this->data['new_items']); if ($item) { ?>
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
            <div id="i2" class="item_container">
                <?php for($i=0;$i<8;$i++) { $item = array_shift($this->data['new_items']); if ($item) { ?>
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

    <div class="banners clearfix">
        <div id="banner1" class="banner1"><a href="#">
                <p class="name">Заголовок<br><span>Промо-товара</span></p>
            </a></div>
        <div id="banner2" class="banner1"><a href="#">
                <p class="name">Заголовок<br><span>Промо-товара</span></p>
            </a></div>
        <div id="banner3" class="banner2"><a href="#">
                <p class="name">Заголовок<br><span>Промо-товара</span></p>
            </a></div>
    </div>

    <div class="items clearfix">
        <div class="items_head">
            <h2>Популярные товары</h2>
            <div class="arrows"><a href="#" onclick="scroll_left(event,'i3','i4','l3','l4');"><img id="l3" src="images/lg.png" alt="l"></a><a href="#" onclick="scroll_right(event,'i3','i4','l3','l4');"><img id="l4" src="images/rb.png" alt="l"></a></div>
        </div>
        <div class="item_wrapper w1">
            <div id="i3" class="item_container">
                <?php for($i=0;$i<4;$i++) { $item = array_shift($this->data['pop_items']); if ($item) { ?>
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
                <?php for($i=0;$i<4;$i++) { $item = array_shift($this->data['pop_items']); if ($item) { ?>
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

    <div class="banners banner_bottom clearfix">
        <h3>О магазине</h3>
        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Delectus deleniti explicabo in iste modi numquam perferendis placeat porro qui quos! Ad aperiam consequuntur doloribus earum ex exercitationem nisi perspiciatis, soluta!</p>
        <p>Nulla consequat massa quis enim. Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu. In enim justo, rhoncus ut, imperdiet a, venenatis vitae, justo. Nullam dictum felis eu pede mollis pretium. Integer tincidunt.</p>
    </div>
