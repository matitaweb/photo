<?php 

/*
This script scan all sub-dirs in img directory to build
image_list.php file that contains all img link used by nano2

*/

function readDirFirstLevel($dir) { 
   
   $result = array(); 

   $cdir = scandir($dir); 
   foreach ($cdir as $key => $value) 
   { 
      if (!in_array($value,array(".",".."))) 
      { 
         if (is_dir($dir . DIRECTORY_SEPARATOR . $value)) 
         { 
            $result[] =$value; 
         } 
      } 
   } 
   
   return $result; 
} 

function dirToArray($album, $img_base_folder, $extensions) { 
   
    $qt_folder_path = $album['dirPath'];
    
    $result = array(); 
    if(!file_exists($qt_folder_path)):
        return $result;
    endif;
    
    $rii = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($qt_folder_path));

    foreach ($rii as $fileinfo) :
    
        if ($fileinfo->isDir()): 
            continue;
        endif;
        
        $extension = strtolower(pathinfo($fileinfo->getFilename(), PATHINFO_EXTENSION));
        
        // check if extension match
        if (!in_array($extension, $extensions)) :
            continue;
        endif;
        
        $qt_file_full_path = $fileinfo->getPathname(); 
        list($width, $height) = getimagesize($qt_file_full_path); 

        
        $rel_file_path = $img_base_folder .DIRECTORY_SEPARATOR. $album['name']. str_replace($qt_folder_path, "", $qt_file_full_path); 
        
        $qt_file_name =  str_replace(".".$extension, "", $fileinfo->getFilename());
    
        $result[] =['filename' => $qt_file_name, 'filepath' => $rel_file_path, 'extension' => $extension, 'width' => $width, 'height' => $height ] ; 

    endforeach;
    
    return $result;  
} 



$config_ini_array = parse_ini_file("..". DIRECTORY_SEPARATOR."config.ini");
$extensions = $config_ini_array['img_extensions']; 
$album_dirs = $config_ini_array['album_dirs']; 
$qt_folder_name = $config_ini_array['img_thumb_relative_path']; 

$upper_dir = str_replace(DIRECTORY_SEPARATOR."src".DIRECTORY_SEPARATOR, DIRECTORY_SEPARATOR."", dirname(realpath( __FILE__ )).DIRECTORY_SEPARATOR);
$qt_folder_path =  $upper_dir.  $qt_folder_name . DIRECTORY_SEPARATOR;


//defile albums
if(empty($album_dirs)){
    // read automatically first level dir
    $album_dirs = readDirFirstLevel($qt_folder_path);
}

$albumList = array(); 
foreach ($album_dirs as $album) :
    $albumList[] =['name' => $album, 'dirPath' => $qt_folder_path.$album ] ; 
endforeach;


$filename = $upper_dir . "image_list.php";
unlink($filename); // delete file
$fh = fopen($filename, 'a');
    
$album_counter = 0;
$image_counter = 0;
foreach ($albumList as $album) :
    $filelist = dirToArray($album, $qt_folder_name, $extensions);
    if(empty($filelist)):
        continue;
    endif;
    
    $image_counter = $image_counter + 1;
    $album_counter = $image_counter;
    
    // first album row
    $album_name = $album['name'];
    $album_row = '<a href="" data-ngkind="album" data-ngid="'.$image_counter.'" data-ngthumb="'.$filelist[0]['filepath'].'" data-ngdesc="" data-ngcustomData=\'{ "incart": false, "idx":'.$image_counter.', "albumname":"'.$album_name.'"'.'}\'  >ALBUM: '.$album_name.'</a>';
    fwrite($fh, $album_row.PHP_EOL);
    
    // add all rows
    $image_album_counter = 0;
    foreach ($filelist as $fileElem) :
        $image_counter = $image_counter + 1;
        $image_row = '<a id="img_'.$image_counter.'" href="'.$fileElem['filepath'].'" data-ngalbumid="'.$album_counter.'"  data-ngid="'.$image_counter.'" data-ngthumb="'.$fileElem['filepath'].'" data-ngdesc="" data-ngcustomData=\'{ "incart": false, "idx":'.$image_counter.', "albumname":"'.$album_name.'"'.'}\'  >'.$fileElem['filename'].'</a>';
        fwrite($fh, $image_row.PHP_EOL);    
        $image_album_counter = $image_album_counter+1;
    endforeach;
    
    echo "Loaded album: [" .$album['name'] . "] tot img: " .$image_album_counter . "<br/>";
    
endforeach;
fclose($fh);


?> 
