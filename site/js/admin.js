function cs_show(id) {
    document.getElementById(id).style.display = "block";
}
function cs_hide(obj) {
    obj.style.display = "none";
}
function redraw_left_block() {
    document.getElementById('left_container').style.display = 'none';
    document.getElementById('left_container').style.display = 'block';
}
function add_var(event) {
    var td = document.getElementById("item_vars");
    var div = document.createElement('div');
    div.innerHTML = "<input name='vars[]' type='text'  placeholder='Вариация' required><a href='#' class='hover' onclick='remove_var(event,this.parentNode);'>Удалить</a>";
    td.appendChild(div);

    redraw_left_block();
    event.preventDefault();
}
function remove_var(event, element) {
    element.parentNode.removeChild(element);
    event.preventDefault();
}
function submit_form(event, name, req) {
    var check = true;
    if (req)
        for(var i=0; i<req.length; i++) {
            var field = document.getElementsByName(req[i]);
            if (field[0].value == null || field[0].value == "") {
                field[0].style.borderColor = "red";
                check=false;
            }
        }
    if (check) {
        var form = document.getElementsByName(name);
        form[0].submit();
    }
    event.preventDefault();
    return check;
}
function ch_zstatus() {
    var frame = document.getElementById('zstatus').contentDocument;

    if (frame.getElementById("error") == null || (frame.getElementById("error") != null && frame.getElementById("error").innerHTML !="0"))
        return;

    var id = frame.getElementById("order_id").innerHTML;
    var status_id = frame.getElementById("status_id").innerHTML;
    var status_text = frame.getElementById("status_text").innerHTML;

    var td = document.getElementById('stclass'+id);
    var a = document.getElementById('stname'+id);

    a.innerHTML = status_text;
    if (td.tagName == "TD")
        td.className = "t_order_status st"+status_id;
    else {
        td.className = "st"+status_id;
        a.className = "st"+status_id;
        var del_links = document.getElementsByName("del_item");
        var class_name = "";
        if (status_id >=4)
            class_name="deleted";
        for (var i = 0; i < del_links.length; i++)
            del_links[i].className = class_name;

    }
}
function del_zitem() {
    var frame = document.getElementById('del_zitem').contentDocument;

    if (frame.getElementById("error") == null || (frame.getElementById("error") != null && frame.getElementById("error").innerHTML !="0"))
        return;

    var item_deleted_id = frame.getElementById("item_deleted_id").innerHTML;
    var order_sum = frame.getElementById("order_sum").innerHTML;

    var sum = document.getElementById('order_sum');
    var tr = document.getElementById('zitem'+item_deleted_id);

    sum.innerHTML = order_sum;
    tr.className = "deleted";
}
function photo_change(id) {
    var file = document.getElementById('file_img'+id);
    var hidden = document.getElementById('img_deleted'+id);
    var label = document.getElementById('label_img'+id);
    var link = document.getElementById('del_img'+id);
    var photo = document.getElementById('photo_img'+id);

    if (file.value == "") {
        hidden.value = "1";
        label.className = "photo_upload";
        label.innerHTML = "Загрузить";
        link.className = "photo_delete deleted";
        photo.className = "photo_image";
        photo.innerHTML = "не загружено";
        photo.style.paddingTop = "36%";
    } else {
        hidden.value = "0";
        label.className = "photo_upload change";
        label.innerHTML = "Изменить";
        link.className = "photo_delete";
        photo.className = "photo_image img";
        photo.innerHTML = file.value.split(/(\\|\/)/g).pop();
        var pad = photo.innerHTML.length / 15;
        var pd = 36;
        if (pad>3)
            pd =10;
        else if (pad > 2)
            pd = 20;
        else if (pad > 1)
            pd = 30;
        photo.style.paddingTop = pd +"%";
    }
}
function photo_del(event, id) {
    var file = document.getElementById('file_img'+id);
    var hidden = document.getElementById('img_deleted'+id);
    var label = document.getElementById('label_img'+id);
    var link = document.getElementById('del_img'+id);
    var photo = document.getElementById('photo_img'+id);

    hidden.value = "1";
    file.value = "";
    label.className = "photo_upload";
    label.innerHTML = "Загрузить";
    link.className = "photo_delete deleted";
    photo.className = "photo_image";
    photo.innerHTML = "не загружено";
    photo.style.paddingTop = "36%";

    event.preventDefault();
}