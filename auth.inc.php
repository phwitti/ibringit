<?php

class Authentication {

    private static $admin_token = null;

    public static function Initialize() {
        global $config;

        $has_admin_token = isset($_GET['admin-token']);

        if (!$has_admin_token) {
            return;
        }

        if (preg_match("#^[a-zA-Z0-9]+$#", $_GET['admin-token']) && $config->admin_token == $_GET['admin-token']) {
            self::$admin_token = $_GET['admin-token'];
        }
    }

    public static function HasAccess()
    {
        return self::$admin_token != null;
    }

    public static function GetToken()
    {
        return self::$admin_token;
    }
}

Authentication::Initialize();

?>
