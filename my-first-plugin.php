<?php
// Plugin Header
/*
Plugin Name: My First Plugin
Description: My first plugin
Version: 1.0.0
Author: Nafiseh
*/
// وقتی پلاگین فعال شود یک پیام روی پنل مدیریت نشان میدهد
function my_first_plugin_notice() {

    echo '<div class="notice notice-success is-dismissible">';
    echo '<p>My First Plugin فعال شد!</p>';
    echo '</div>';

}

add_action(
    'admin_notices',
    'my_first_plugin_notice'
);
//نمایش پیام هنگام فعالسازی پلاگین
//این متد به وردپرس میگوبد وقتی افزونه من فعال شد تابع 
// my_first_plugin_activate را اجرا کن
//__FILE__مسیرفایل اصلی افزونه است.
// __FILE__---> php یک ثابت جادویی در 
// این ثابت، مسیر فایل فعلی را به‌صورت یک رشته برمی‌گرداند؛ معمولاً مسیر کامل فایل، همراه با نام فایل.
register_activation_hook(
    __FILE__,
    'my_first_plugin_activate'
);

function my_first_plugin_activate() {
    update_option(
        'my_first_plugin_status',
        'پلاگین فعال شد'
    );
}
//نمایش پیام هنگام غیرفعالسازی پلاگین
//این متد به وردپرس میگوبد وقتی افزونه من غیرفعال شد تابع 
// my_first_plugin_deactivate را اجرا کن
register_deactivation_hook(
    __FILE__,
    'my_first_plugin_deactivate'
);

function my_first_plugin_deactivate() {
    update_option(
        'my_first_plugin_status',
        'پلاگین غیرفعال شد'
    );
}