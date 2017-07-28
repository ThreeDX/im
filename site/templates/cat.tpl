    <div class="title_block">
        <div class="title"><?=$this->category['name'];?></div>
        <div class="cat_items">Показано <?php if($this->category['count'] == 0) echo 0; else echo $this->offset; ?>-<?=($this->offset - 1 + count($this->data['items']));?> из <?=$this->category['count'];?> товаров</div>
    </div>

    <div class="items clearfix">
        <div class="pag_head">
            <div class="pag">Страницы <?php for($i=1;$i<=$this->max_page;$i++) { ?><a href="index.php?page=Index&cat=<?=$this->category['id'];?>&p=<?=$i;?>"<?php if ($i==$this->current_page) { ?> class="current"<?php } ?>><?=$i;?></a><?php } ?></div>
        </div>
        <div class="item_wrapper wc">
            <?php if($this->current_page == 1) { ?>
            <div id="banner4" class="banner3"><a href="#">
                <p class="name"><?=$this->category['name'];?></p>
            </a></div>
            <?php for($i=0;$i<11;$i++) { $item = array_shift($this->data['items']); if ($item) { ?>
            <div class="item c"><a href="index.php?page=Item&id=<?=$item['id'];?>">
                    <div class="image"><?php if ($item['images'][1]) { ?><img src="images/items/k<?=$item['images'][1];?>" alt="<?=$item['name'];?>"><?php } else { ?><img src="images/image.jpeg" alt="image"><?php } ?></div>
                    <?php if ($item['bage'] == 1) { ?><div class="label"><img src="images/new.png" alt="new"></div><?php } ?>
                    <?php if ($item['bage'] == 2) { ?><div class="label"><img src="images/hot.png" alt="hot"></div><?php } ?>
                    <?php if ($item['bage'] == 3) { ?><div class="label"><img src="images/sale.png" alt="sale"></div><?php } ?>
                    <p><?=$item['name'];?></p>
                    <p class="price"><?=$item['price'];?><span>руб.</span><?php if ($item['price_old']) { ?><span><br><?=$item['price_old'];?> руб.</span><?php } ?></p>
                </a></div>
            <?php } } ?>
            <div id="banner5" class="banner4">
                <p class="name">Заголовок<br>Промо-товара</p>
                <p class="description">Описание промо-товара</p>
                <p class="price_promo">4 540<span>руб.</span></p>
                <a href="#">Посмотреть +</a>
            </div>
            <?php for($i=0;$i<6;$i++) { $item = array_shift($this->data['items']); if ($item) { ?>
            <div class="item c"><a href="index.php?page=Item&id=<?=$item['id'];?>">
                    <div class="image"><?php if ($item['images'][1]) { ?><img src="images/items/k<?=$item['images'][1];?>" alt="<?=$item['name'];?>"><?php } else { ?><img src="images/image.jpeg" alt="image"><?php } ?></div>
                    <?php if ($item['bage'] == 1) { ?><div class="label"><img src="images/new.png" alt="new"></div><?php } ?>
                    <?php if ($item['bage'] == 2) { ?><div class="label"><img src="images/hot.png" alt="hot"></div><?php } ?>
                    <?php if ($item['bage'] == 3) { ?><div class="label"><img src="images/sale.png" alt="sale"></div><?php } ?>
                    <p><?=$item['name'];?></p>
                    <p class="price"><?=$item['price'];?><span>руб.</span><?php if ($item['price_old']) { ?><span><br><?=$item['price_old'];?> руб.</span><?php } ?></p>
                </a></div>
            <?php } } } else { ?>
            <?php for($i=0;$i<24;$i++) { $item = array_shift($this->data['items']); if ($item) { ?>
            <div class="item"><a href="index.php?page=Item&id=<?=$item['id'];?>">
                    <div class="image"><?php if ($item['images'][1]) { ?><img src="images/items/k<?=$item['images'][1];?>" alt="<?=$item['name'];?>"><?php } else { ?><img src="images/image.jpeg" alt="image"><?php } ?></div>
                    <?php if ($item['bage'] == 1) { ?><div class="label"><img src="images/new.png" alt="new"></div><?php } ?>
                    <?php if ($item['bage'] == 2) { ?><div class="label"><img src="images/hot.png" alt="hot"></div><?php } ?>
                    <?php if ($item['bage'] == 3) { ?><div class="label"><img src="images/sale.png" alt="sale"></div><?php } ?>
                    <p><?=$item['name'];?></p>
                    <p class="price"><?=$item['price'];?><span>руб.</span><?php if ($item['price_old']) { ?><span><br><?=$item['price_old'];?> руб.</span><?php } ?></p>
                </a></div>
            <?php } } ?>
            <?php } ?>
        </div>
        <div class="pag_head bottom">
            <div class="pag">Страницы <?php for($i=1;$i<=$this->max_page;$i++) { ?><a href="index.php?page=Index&cat=<?=$this->category['id'];?>&p=<?=$i;?>"<?php if ($i==$this->current_page) { ?> class="current"<?php } ?>><?=$i;?></a><?php } ?></div>
        </div>
    </div>