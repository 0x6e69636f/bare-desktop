<?php

$apps_dir_content = scandir('App');
$apps = array();

foreach($apps_dir_content as $app_filename){
    if($app_filename != '.' && $app_filename != '..'){
        $app_name_split = preg_split("/\./",$app_filename);
        array_pop($app_name_split);
        $app_name = join("",$app_name_split);

        $app_name_class = "App.".$app_name."";
        array_push($apps, array("name"=>$app_name, "class_name"=>$app_name_class));
    }
}

echo json_encode($apps);
