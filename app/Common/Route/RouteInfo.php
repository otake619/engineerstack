<?php

namespace app\Common\Route;

class RouteInfo
{
    /**
     * ルート情報を取得
     */
    public static function getRouteInfo($param){
        if($param->routeIs(config('route.admin_wild'))){
            return 'admins';
        }
        return 'users';
    }
}