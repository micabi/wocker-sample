<?php
/**
 * Plugin Name:     My Custom Admin
 * Plugin URI:      PLUGIN SITE HERE
 * Description:     テスト用
 * Author:          Wataru Adachi
 * Author URI:      https://www.codecode.xyz
 * Text Domain:     my-custom-admin
 * Domain Path:     /languages
 * Version:         0.1.0
 *
 * @package         My_Custom_Admin
 */

add_action('admin_menu', 'my_admin_menu');
function my_admin_menu(){
  add_menu_page(
    'My Admin', // ブラウザーのタブに表示されるタイトル
    'My Admin', // メニュータイトル
    'administrator', // 権限者
    'my_custom_menu', // スラッグ
    'my_custom_menu' // コールバック関数
    );

  add_submenu_page(
     'my_custom_menu', // 親となるメニューのスラッグ
     'My Admin Sub', // ブラウザーのタブに表示されるタイトル
     'My Admin Sub', // サブメニュータイトル
     'administrator', // 権限者
     'my_custom_submenu', // スラッグ
     'my_custom_submenu' // コールバック関数
	);

  add_options_page(
	'フォームを追加',
	'フォームを追加',
	'administrator',
	'add_my_form',
	'add_my_form'
  );
}

function my_custom_menu(){
?>
  <div class="wrap">
    <h2>My Admin</h2>
  </div><!-- /.wrap -->
<?php
}

function my_custom_submenu(){
  ?>
    <div class="wrap">
      <h2>My Admin Sub</h2>
    </div><!-- /.wrap -->
  <?php
  }

function add_my_form(){
  ?>
	<div class="wrap">
		<h2>フォームを追加</h2>
		<form action="" method="POST">
			<?php wp_nonce_field('my-nonce', 'add_my_form'); ?><!-- CSRF対策 -->
			<p><input type="text" name="text-data" value="<?php echo esc_attr( get_option('text-data') ); ?>"></p>
			<p><input type="submit" value="保存" class="button button-primary button-large"></p>
		</form>
	</div><!-- /.wrap -->
  <?php
}

add_action('admin_init', 'my_admin_init'); // フォームからの送信内容を保存
function my_admin_init(){
	if( isset( $_POST['add_my_form'] ) && $_POST['add_my_form'] ){
		if( check_admin_referer( 'my-nonce', 'add_my_form' ) ){
			var_dump($_POST['text-data']);
		}

	}
}

