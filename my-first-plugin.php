<?php
// Plugin Header
/*
Plugin Name: My First Plugin
Description: My first plugin
Version: 1.0.0
Author: Nafiseh
*/
// وقتی پلاگین فعال باشد یک پیام روی پنل مدیریت،هر وقت صفحه پیشخوان بارگذاری شود نشان میدهد
function my_first_plugin_notice() {

    echo '<div class="notice notice-success is-dismissible">';
    echo '<p>My First Plugin فعال شد!</p>';
    echo '</div>';

}

add_action(
    'admin_notices',
    'my_first_plugin_notice'
);
//نمایش پیام فقط بعد از فعالسازی نمایش بده

// function my_first_plugin_activate() {
//     update_option('my_first_plugin_show_notice', 'yes');
// }

// register_activation_hook(__FILE__, 'my_first_plugin_activate');


// function my_first_plugin_notice() {

//     if (get_option('my_first_plugin_show_notice') !== 'yes') {
//         return;
//     }

//     echo '<div class="notice notice-success is-dismissible">';
//     echo '<p>My First Plugin فعال شد!</p>';
//     echo '</div>';

//     delete_option('my_first_plugin_show_notice');
// }

// add_action('admin_notices', 'my_first_plugin_notice');

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
// نمایش اطلاعات افزونه در سایت
//مرحله اول:ساخت تابع نمایش اطلاعات تماس (ایمیل و شماره تلفن)

function my_first_plugin_contact_info() {

    $phone = get_option('my_phone', '');
    $email = get_option('my_email', '');

    if (empty($phone) && empty($email)) {
        return '';
    }

    $output = '<div class="my-plugin-contact">';

    if (!empty($phone)) {
        $output .= '<p>شماره تماس: ' . esc_html($phone) . '</p>';
    }

    if (!empty($email)) {
        $output .= '<p>ایمیل: ' . esc_html($email) . '</p>';
    }

    $output .= '</div>';

    return $output;
}
//مرحله دوم:ساخت شورت کد

function my_first_plugin_contact_shortcode() {
    return my_first_plugin_contact_info();
}

add_shortcode(
    'my_contact_info',
    'my_first_plugin_contact_shortcode'
);
//مرحله سوم:قرار دادن شورت کد در هر برگه یا نوشته ای که میخواهی نمایش داده بشه [my_contact_info]
// ***********************************************************
//نمایش متن فوتر
//مرحله اول : ساخت تابع نمایش متن فوتر
function my_first_plugin_footer_text() {

    $footer_text = get_option('my_footer_text', '');

    if (empty($footer_text)) {
        return '';
    }

    return '<div class="my-plugin-footer">'
        . nl2br(esc_html($footer_text))
        . '</div>';
}
//مرحله دوم : ساخت شورت کد

add_shortcode(
    'my_footer_text',
    'my_first_plugin_footer_text'
);
//مرجله سوم : نمایش خودکار متن در فوتر سایت

function my_first_plugin_display_footer_text() {

    echo my_first_plugin_footer_text();

}

add_action(
    'wp_footer',
    'my_first_plugin_display_footer_text'
);


// ***********************************************************
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

//  **********************************************
//اضافه کردن استایل به افزونه

function my_first_plugin_enqueue_styles() {

    wp_enqueue_style(
        'my-first-plugin-style',
        plugin_dir_url(__FILE__) . 'assets/style.css',
        array(),
        '1.0.0'
    );

}

add_action(
    'wp_enqueue_scripts',
    'my_first_plugin_enqueue_styles'
);
// shorcode ساخت
// شورت کدی که 5 نوشته آخر را در هر صفحه نمایش دهد

// function my_first_plugin_latest_posts() {

//     $posts = get_posts(array(
//         'numberposts' => 5,
//         'post_status' => 'publish',
//         'post_type'   => 'post',
//         'orderby'     => 'date',
//         'order'       => 'DESC',
//     ));

//     if (empty($posts)) {
//         return '<p>هنوز نوشته‌ای منتشر نشده است.</p>';
//     }

//     $output = '<ul class="my-plugin-latest-posts">';

//     foreach ($posts as $post) {
//         $output .= '<li>';
//         $output .= '<a href="' . esc_url(get_permalink($post->ID)) . '">';
//         $output .= esc_html(get_the_title($post->ID));
//         $output .= '</a>';
//         $output .= '</li>';
//     }

//     $output .= '</ul>';

//     return $output;
// }

// add_shortcode(
//     'latest_posts',
//     'my_first_plugin_latest_posts'
// );
//شورت کدی که تعداد نوشته ها برای نمایش را از کاربر بگیرد

function my_first_plugin_latest_posts($atts) {

    $atts = shortcode_atts(
        array(
            'count' => 5,
        ),
        $atts,
        'latest_posts'
    );

    $count = absint($atts['count']);
    $count = max(1, min($count, 20));

    $posts = get_posts(array(
        'numberposts' => $count,
        'post_status' => 'publish',
        'post_type'   => 'post',
        'orderby'     => 'date',
        'order'       => 'DESC',
    ));

    if (empty($posts)) {
        return '<p>هنوز نوشته‌ای منتشر نشده است.</p>';
    }

    $output = '<ul class="my-plugin-latest-posts">';

    foreach ($posts as $post) {
        $output .= '<li>';
        $output .= '<a href="' . esc_url(get_permalink($post->ID)) . '">';
        $output .= esc_html(get_the_title($post->ID));
        $output .= '</a>';
        $output .= '</li>';
    }

    $output .= '</ul>';

    return $output;
}

add_shortcode(
    'latest_posts',
    'my_first_plugin_latest_posts'
);
// ساخت ابزارک کلاسیک

class My_First_Plugin_Widget extends WP_Widget {

    public function __construct() {
        parent::__construct(
            'my_first_plugin_widget',
            'ابزارک افزونه من',
            array(
                'description' => 'نمایش یک پیام ساده در سایت'
            )
        );
    }
   
//میخوایم کاری کنیم که عنوان و متن ابزارک از پیشخوان دریافت بشه 
    
public function widget($args, $instance) {
    $title = !empty($instance['title'])
        ? $instance['title']
        : 'پیام افزونه من';

    $message = !empty($instance['message'])
        ? $instance['message']
        : 'به سایت من خوش آمدید!';

    echo $args['before_widget'];

    echo $args['before_title'];
    echo esc_html($title);
    echo $args['after_title'];

    echo '<p>' . esc_html($message) . '</p>';

    echo $args['after_widget'];
}

public function form($instance) {
    $title = !empty($instance['title'])
        ? $instance['title']
        : 'پیام افزونه من';

    $message = !empty($instance['message'])
        ? $instance['message']
        : 'به سایت من خوش آمدید!';

    ?>
    <p>
        <label for="<?php echo esc_attr($this->get_field_id('title')); ?>">
            عنوان:
        </label>

        <input
            class="widefat"
            id="<?php echo esc_attr($this->get_field_id('title')); ?>"
            name="<?php echo esc_attr($this->get_field_name('title')); ?>"
            type="text"
            value="<?php echo esc_attr($title); ?>"
        >
    </p>

    <p>
        <label for="<?php echo esc_attr($this->get_field_id('message')); ?>">
            متن پیام:
        </label>

        <textarea
            class="widefat"
            id="<?php echo esc_attr($this->get_field_id('message')); ?>"
            name="<?php echo esc_attr($this->get_field_name('message')); ?>"
            rows="4"
        ><?php echo esc_textarea($message); ?></textarea>
    </p>
    <?php
}

}

function my_first_plugin_register_widget() {
    register_widget('My_First_Plugin_Widget');
}

add_action(
    'widgets_init',
    'my_first_plugin_register_widget'
);
