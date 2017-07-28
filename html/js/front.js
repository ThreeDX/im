function scroll_right(e, i1, i2, l1, l2) {
    document.getElementById(i1).style.opacity = 0;
    document.getElementById(i2).style.opacity = 1;
    document.getElementById(i1).style.zIndex = 1;
    document.getElementById(i2).style.zIndex = 2;
    document.getElementById(l1).src = "images/lb.png";
    document.getElementById(l2).src = "images/rg.png";
    e.preventDefault();
}
function scroll_left(e, i1, i2, l1, l2) {
    document.getElementById(i1).style.opacity = 1;
    document.getElementById(i2).style.opacity = 0;
    document.getElementById(i1).style.zIndex = 2;
    document.getElementById(i2).style.zIndex = 1;
    document.getElementById(l1).src = "images/lg.png";
    document.getElementById(l2).src = "images/rb.png";
    e.preventDefault();
}
function ch_cart_add() {
    var frame = document.getElementById('cart_add').contentDocument;

    if (frame.getElementById("error") == null || (frame.getElementById("error") != null && frame.getElementById("error").innerHTML !="0"))
        return;

    var count = frame.getElementById("count").innerHTML;
    var sum = frame.getElementById("sum").innerHTML;

    var modal = document.getElementById('modal');
    var cart_count = document.getElementById('cart_count');
    var cart_sum = document.getElementById('cart_sum');

    modal.className="modal";
    cart_count.innerHTML = count;
    cart_sum.innerHTML = sum;
}
function ch_cart_del() {
    var frame = document.getElementById('cart_del').contentDocument;

    if (frame.getElementById("error") == null || (frame.getElementById("error") != null && frame.getElementById("error").innerHTML !="0"))
        return;

    var count = frame.getElementById("count").innerHTML;
    var sum = frame.getElementById("sum").innerHTML;
    var id = frame.getElementById("id").innerHTML;

    var cart_count = document.getElementById('cart_count');
    var cart_sum = document.getElementById('cart_sum');
    var cart_info_sum = document.getElementById('cart_info_sum');
    var tr = document.getElementById(id);
    var order = document.getElementById("order_button");

    cart_count.innerHTML = count;
    cart_sum.innerHTML = sum;
    cart_info_sum.innerHTML = sum+'руб.';
    tr.style.display = "none";
    if(sum==0)
        order.style.display="none";
}
function ch_cart_count() {
    var frame = document.getElementById('cart_fcount').contentDocument;

    if (frame.getElementById("error") == null || (frame.getElementById("error") != null && frame.getElementById("error").innerHTML !="0"))
        return;

    var count = frame.getElementById("count").innerHTML;
    var count_item = frame.getElementById("count_item").innerHTML;
    var sum_item = frame.getElementById("sum_item").innerHTML;
    var sum = frame.getElementById("sum").innerHTML;
    var id = frame.getElementById("id").innerHTML;

    var cart_count = document.getElementById('cart_count');
    var cart_sum = document.getElementById('cart_sum');
    var cart_info_sum = document.getElementById('cart_info_sum');
    var count_new = document.getElementById('c'+id);
    var sum_new = document.getElementById('s'+id);

    cart_count.innerHTML = count;
    cart_sum.innerHTML = sum;
    cart_info_sum.innerHTML = sum+'руб.';
    count_new.innerHTML = count_item;
    sum_new.innerHTML = sum_item+'руб.';
}
function ch_count(id, value) {
    var count = document.getElementById('c'+id);
    var nc = parseInt(count.innerHTML) + value;
    if(nc <= 0)
        return;

    var a = document.createElement('a');
    a.href='index.php?page=Cart&action=count&id='+id+'&count='+nc;
    a.target = 'cart_fcount';
    //document.body.appendChild(a);
    a.click();
}
function set_image(im_id) {
    var big = document.getElementById('image_big');
    var big_modal = document.getElementById('image_big_modal');
    var current = document.getElementById('image_gal'+current_image);
    var now = document.getElementById('image_gal'+im_id);
    var image = now.getAttribute('data-image');
    current.className = "small_image";
    now.className = "small_image current";
    big.src = "images/items/l"+image;
    big_modal.src = "images/items/"+image;
    current_image = im_id;
}
function shift_image(v) {
    if(max_image<0)
        return;
    var im_id = current_image + v;
    if (im_id > max_image)
        im_id=0;
    if (im_id < 0)
        im_id=max_image;

    set_image(im_id);
}