<?php
/*
Plugin Name: CMS Default Chromos Planet
Plugin URI: http://chromosplanet.com.br
Description: Este plugin foi desenvolvido pela Chromos Planet, para a criação de CMS 
Version: 1.0.1
Author: Bmordente
Author URI: http://chromosplanet.com.br
*/


/*////////////////////// Desabilitar Aviso IE /////////////////////*/
function disable_browser_upgrade_warning() {
    remove_meta_box( 'dashboard_browser_nag', 'dashboard', 'normal' );
}
add_action( 'wp_dashboard_setup', 'disable_browser_upgrade_warning' );


/*////////////////////// Desabilitar Cores Wordpress /////////////////////*/
function admin_color_scheme() {
   global $_wp_admin_css_colors;
   $_wp_admin_css_colors = 0;
}
add_action('admin_head', 'admin_color_scheme');

/*////////////////////// Alterar texto rodape /////////////////////*/
function dcp_remove_footer_admin () {
    echo "Desenvolvido pela <a href='http://www.chromosplanet.com.br' target='_blank'>Chromos Planet</a>";
} 
add_filter('admin_footer_text', 'dcp_remove_footer_admin');


/*////////////////////// Desabilitar menu esquerdo /////////////////////*/
function dcp_remove_menus () {
    if (!current_user_can('delete_users')) {	
        global $menu;
        $restricted = array(__('Links'), __('Comments'), __('Plugins'), __('Tools'), __('Settings'));
        end ($menu);
        while (prev($menu)){
            $value = explode(' ',$menu[key($menu)][0]);
            if(in_array($value[0] != NULL?$value[0]:"" , $restricted)){unset($menu[key($menu)]);}
        }
	}
}
add_action('admin_menu', 'dcp_remove_menus');

/*////////////////////// Desabilitar menu superior /////////////////////*/

function dcp_admin_bar() {
    global $wp_admin_bar;
    $wp_admin_bar->remove_menu('wp-logo');
    $wp_admin_bar->remove_menu('about');
    $wp_admin_bar->remove_menu('wporg');
    $wp_admin_bar->remove_menu('documentation');
    $wp_admin_bar->remove_menu('support-forums');
    $wp_admin_bar->remove_menu('feedback');
    $wp_admin_bar->remove_menu('view-site');
	$wp_admin_bar->remove_menu('comments');
	$wp_admin_bar->remove_menu('updates');
}
add_action( 'wp_before_admin_bar_render', 'dcp_admin_bar' );


/*////////////////////// Desabilitar seções da dashboard /////////////////////*/

function dcp_del_secoes_painel(){
  global$wp_meta_boxes;
  unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_plugins']);
  unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_recent_comments']);
  unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_primary']);
  unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_incoming_links']);
  unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_right_now']);
  unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_secondary']);
  unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_quick_press']);

}
 
add_action('wp_dashboard_setup', 'dcp_del_secoes_painel');


/////////////////////////// Desabilitar feed ////////////////////////////
function dcp_disable_feed() {
wp_die( __('No feed available,please visit our <a href="'. get_bloginfo('url') .'">homepage</a>!') );
}

add_action('do_feed', 'dcp_disable_feed', 1);
add_action('do_feed_rdf', 'dcp_disable_feed', 1);
add_action('do_feed_rss', 'dcp_disable_feed', 1);
add_action('do_feed_rss2', 'dcp_disable_feed', 1);
add_action('do_feed_atom', 'dcp_disable_feed', 1);
add_action('do_feed_rss2_comments', 'dcp_disable_feed', 1);
add_action('do_feed_atom_comments', 'dcp_disable_feed', 1);


// Dando permissão para usuarios do tipo Editor gerenciarem os Widgets

if(is_admin()){
	$role =& get_role('editor');
	$role->add_cap('edit_theme_options');
	$role->add_cap('manage_options');
	$role->add_cap('manage_polls');
	$role->remove_cap('switch_themes');
}


/*////////////////////// Widget Suporte /////////////////////*/

add_action('wp_dashboard_setup', 'dcp_custom_dashboard_widgets');
 
function dcp_custom_dashboard_widgets() {
global $wp_meta_boxes;

wp_add_dashboard_widget('custom_help_widget', 'Suporte', 'dcp_custom_dashboard_help');
}


function dcp_custom_dashboard_help() {
$output = '<h4><strong>Precisa de ajuda?</strong></h4>'.
          '<h4>Entre em contato:</h4>'.
          '<p>E-mail:<a href="mailto:contato@chromosplanet.com" target="_blank"> contato@chromosplanet.com.br</a><br />'.
          'Site:<a href="http://www.chromosplanet.com.br" target="_blank"> www.chromosplanet.com.br</a><br />'.
          'Facebook:<a href="http://facebook.com/chromosplanet" target="_blank"> facebook.com/chromosplanet</a><br />'.
          'Endereço: <a href="http://goo.gl/maps/Vc8gq" target="_blank">Rua Líbero Badaró, 183, Jaraguá, Belo Horizonte, Brasil</a></p>'.
          '<h4>Telefone: (31) 3443-6719</h4>';

echo $output;

}

/*//////////////////////// Altera logo ///////////////////////*/
function minha_logo_login()
{
	$plugins_url = plugins_url();
    echo '<style  type="text/css">.login h1 a {  background-image:url('.$plugins_url.'/cp_default/images/logo.png)  !important;height:155px !important;background-size:274px; } </style>';
}
add_action('login_head',  'minha_logo_login');



/*//////////////////////// Mensagem Login ///////////////////////*/
function no_login_error() {
    return 'Oops! Credênciais erradas.';
}
 
add_filter('login_errors','no_login_error');


/*//////////////////////// Capacidade Editor Gravity Forms ///////////////////////*/
function add_theme_caps() {
    $role = get_role( 'editor' );
    $role->add_cap( 'gform_full_access' ); 
}
add_action( 'admin_init', 'add_theme_caps');


?>