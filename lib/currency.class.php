<?php

class Currency {

    // Get by Alias from table products
    public static function getCurrency(){
        $sql = "select * from `currency` limit 1";
        return App::$db->query($sql);
    }

}
