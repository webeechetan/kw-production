<?php
namespace App\Helpers;

use Laravolt\Avatar\Avatar;


class Helper
{

    public static function createAvatar($name,$folder){

        $name = strtoupper($name);

        $avtar_bg_colors = [
            '#f83890',
            '#7407ff',
            '#FF5E0E',
            '#cdb110',
            '#48914d',
        ];
        $avtar = new Avatar();
        $avtar->create($name)->toBase64();
        $avtar->setBackground($avtar_bg_colors[rand(0,4)]);
        // check if folder exist or not if not then create folder
        if(!file_exists(storage_path('app/public/images/'.$folder))){
            mkdir(storage_path('app/public/images/'.$folder), 0777, true);
        }
        $avtar->save(storage_path('app/public/images/'.$folder.'/'.$name.'.png'));
        return 'images/'.$folder.'/'.$name.'.png';
    }

    public static function colors(){
        return ['orange','purple','green','pink','yellow','blue'];
    }

    public static function getOrgName(){
        return session('org_name');
    }
}
?>