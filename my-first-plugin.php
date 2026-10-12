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
// ساخت منوی افزونه در پیشخوان
function my_first_plugin_add_admin_menu() {

    add_menu_page(
        'تنظیمات افزونه من',
        'افزونه من',
        'manage_options',
        'my-first-plugin',
        'my_first_plugin_settings_page',
        'dashicons-admin-generic',
        25
    );

}

add_action(
    'admin_menu',
    'my_first_plugin_add_admin_menu'
);
// محتوای صفحه افزونه من--> ساخت فرم تنطیمات
function my_first_plugin_settings_page() {
    ?>

    <div class="wrap">
        <h1>تنظیمات افزونه من</h1>

        <form  method="post" action="options.php">
            <?php settings_fields('my_first_plugin_settings_group'); ?>
            <table class="form-table">

                <tr>
                    <th>
                        <label for="my_phone">شماره تماس</label>
                    </th>
                    <td>
                        <input
                            type="text"
                            id="my_phone"
                            name="my_phone"
                            class="regular-text"
                            value="<?php echo esc_attr(get_option('my_phone', '')); ?>"
                        >
                    </td>
                </tr>

                <tr>
                    <th>
                        <label for="my_email">ایمیل</label>
                    </th>
                    <td>
                        <input
                            type="email"
                            id="my_email"
                            name="my_email"
                            class="regular-text"
                            value="<?php echo esc_attr(get_option('my_email', '')); ?>"
                        >
                    </td>
                </tr>

                <tr>
                    <th>
                        <label for="my_footer_text">متن فوتر</label>
                    </th>
                    <td>
                        <textarea
                            id="my_footer_text"
                            name="my_footer_text"
                            rows="4"
                            class="large-text"
                           >
                        <?php echo esc_textarea(get_option('my_footer_text', '')); ?>
                        </textarea>
                    </td>
                </tr>

            </table>

            <?php submit_button('ذخیره تنظیمات'); ?>

        </form>
    </div>

    <?php
}
// setting API ثبت تنظیمات با

function my_first_plugin_register_settings() {

    register_setting(
        'my_first_plugin_settings_group',
        'my_phone',
        //نوع داده و روش اعتبارسنجی را هم مشخص میکند.
        array(
            'type' => 'string',
            'sanitize_callback' => 'sanitize_text_field',
            'default' => ''
        )
    );

    register_setting(
        'my_first_plugin_settings_group',
        'my_email',
        //نوع داده و روش اعتبارسنجی را هم مشخص میکند.
        array(
            'type' => 'string',
            'sanitize_callback' => 'sanitize_email',
            'default' => ''
        )
    );

    register_setting(
        'my_first_plugin_settings_group',
        'my_footer_text',
        // متن فوتر، بسته به اینکه چه نوع محتوایی می‌خواهی مجاز باشد نوع داده و روش اعتبارسنجی را هم مشخص میکند.

        array(
            'type' => 'string',
            'sanitize_callback' => 'sanitize_textarea_field',
            'default' => ''
        )
    );

}

add_action(
    'admin_init',
    'my_first_plugin_register_settings'
);
// اضافه کردن یک پیام به انتهای محتوای نوشته با استفاده از هوک

function my_first_plugin_after_content($content) {

    if (is_singular('post') && is_main_query() && in_the_loop()) {
        $content .= '<p>ممنون که این نوشته را مطالعه کردید!</p>';
    }

    return $content;
}

add_filter('the_content', 'my_first_plugin_after_content');
// تغییر عنوان نوشته
function my_first_plugin_change_title($title,$post_id){
    //فقط یک نکته درباره فیلتر عنوان: در بعضی شرایط، مثل عنوان‌های داخل منو یا ابزارک‌ها، ممکنه شرط‌های فعلی کافی نباشن. برای تمرین فعلی، اگر عنوان فقط در صفحه نوشته تکی تغییر می‌کنه، نتیجه قابل قبوله.
    if (is_singular('post') && is_main_query() && in_the_loop()&&get_queried_object_id()===$post_id) {
        $title = ' نفس بلاگ '.$title;
    }

    return $title;
}
add_filter('the_title', 'my_first_plugin_change_title',10,2);

 