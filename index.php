<?php
/*
Plugin Name:  Comptador de temps del projecte
Version    :  1.0
Description:  Time tracker per comptabilitzar el temps emprat en el projecte.
Author     :  Jordi Gonzalez
Author URI :  http://glpi-project.bigmacbrothers.cat/
License    :  GPLv2 or later
License URI:  https://www.gnu.org/licenses/gpl-2.0.html
Text Domain:  projecte glpi - timer del projecte
*/


add_action('admin_menu', 'afegir_menu_llistaTimer');

function afegir_menu_llistaTimer(){
    add_menu_page( 'Timer del Projecte', 'Timer del Projecte', 'manage_options', 'timer-del-projecte', 'pagina_principalTimer' );
}


function pagina_principalTimer(){

    echo "<h1>Timer del Projecte GLPI</h1>";
    echo "<p>Aquí podràs portar el control de temps del projecte.<br></p>";
    require( plugin_dir_path( __FILE__ ) . 'timerprojecte.php');

}




?>