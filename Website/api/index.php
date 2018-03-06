<?php

function tree($path){
  static $match;

  // Find the real directory part of the path, and set the match parameter
  $last=strrpos($path,"/");
  if(!is_dir($path)){
    $match=substr($path,$last);
    while(!is_dir($path=substr($path,0,$last)) && $last!==false)
      $last=strrpos($path,"/",-1);
  }
  if(empty($match)) $match="/*";
  if(!$path=realpath($path)) return;

  // List files
  foreach(glob($path.$match) as $file){
    $list[]=substr($file,strrpos($file,"/")+1);
  }  

  // Process sub directories
  foreach(glob("$path/*", GLOB_ONLYDIR) as $dir){
    $list[substr($dir,strrpos($dir,"/",-1)+1)]=tree($dir);
  }
  
  return @$list;
}

	$response["error"] = FALSE;
    $response['success'] = "You cannot access directly";
    
    $files = tree('/home/mustafa2/cs491-2.mustafaculban.net/api/');

    //echo "<pre>"; print_r($files); echo "</pre>";

    echo json_encode($files);

?>