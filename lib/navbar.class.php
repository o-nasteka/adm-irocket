<?php

class NavBar {


    public static function TopMenu(){

        // Get Menu
        $menu = self::getMenu();

        $template_name = VIEWS_PATH.DS."navbar.html";
        // var_dump($template_name);
        // exit;
        return require("$template_name");

    }

    // getMenu
    private static function getMenu(){
        $sql = "select `alias`,`title`,`parent_id` from `products` ";
        return App::$db->query($sql);
    }

}


?>