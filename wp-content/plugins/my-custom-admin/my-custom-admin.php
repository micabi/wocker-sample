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
	'フォームを追加するプラグイン',
	'フォームを追加するプラグイン',
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
		<h2>設定に「フォームを追加する」プラグイン</h2>
		<form action="" method="POST">
			<?php wp_nonce_field('my-nonce', 'add_my_form'); ?><!-- CSRF対策 -->
			<p><label for="e-mail">E-mail Address: <input type="text" name="text-data" value="<?php echo esc_attr( get_option('text-data') ); ?>" id="e-mail"></label></p>
			<p><input type="submit" value="保存" class="button button-primary button-large"></p>
		</form>
	</div><!-- /.wrap -->
  <?php
}

add_action('admin_init', 'my_admin_init'); // フォームからの送信内容を保存
function my_admin_init(){
	if( !empty( $_POST ) && check_admin_referer( 'my-nonce', 'add_my_form' ) ){
		//var_dump($_POST['text-data']);

		if( isset( $_POST['text-data'] ) && $_POST['text-data'] ){
			$e = new WP_Error();
			if( is_email( trim( $_POST['text-data'] ) ) ){
				update_option( 'text-data', trim( $_POST['text-data'] ) );
			}else{
				$e->add( 'error', 'メールアドレスの形式ではありません。');
				set_transient( 'add-my-form-error', $e->get_error_messages(), 10 );
			}
		}else{
			update_option( 'text-data', '' );
		}

		wp_safe_redirect( menu_page_url( 'add_my_form' ), false );
	}
}

add_action('admin_notices', 'my_admin_notices');
function my_admin_notices(){
?>
	<?php if( $messages = get_transient( 'add-my-form-error' ) ): ?>
	<div class="error">
		<ul>
			<?php foreach( $messages as $message ): ?>
			<li><?php echo esc_html( $message ); ?></li>
			<?php endforeach; ?>
		</ul>
	</div><!-- /.error -->
	<?php endif; ?>
<?php
}

