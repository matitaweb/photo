<?php 

/*
Read json request file in "request" dir.
pack all selected photo coping from img_hd to a dir in "packaged" dir.

*/

$qt_folder_name_from = "requests";
$qt_folder_name_to = "packaged";

$extensions = array('json');


$config_ini_array = parse_ini_file("..". DIRECTORY_SEPARATOR ."config.ini");
$upper_dir = str_replace(DIRECTORY_SEPARATOR."src".DIRECTORY_SEPARATOR, DIRECTORY_SEPARATOR."", dirname(realpath( __FILE__ )).DIRECTORY_SEPARATOR);

$qt_folder_path_from = $upper_dir . $qt_folder_name_from . DIRECTORY_SEPARATOR;
$qt_folder_path_to = $upper_dir . $qt_folder_name_to . DIRECTORY_SEPARATOR;

if (!file_exists($qt_folder_path_from)) {
    mkdir($qt_folder_path_from, 0755, true);
}
if (!file_exists($qt_folder_path_to)) {
    mkdir($qt_folder_path_to, 0755, true);
}


$counter = 0;

$imghd_dir_name = $config_ini_array['img_full_relative_path'];
$imghd_dir_path =$upper_dir.$imghd_dir_name . DIRECTORY_SEPARATOR;


$rii = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($qt_folder_path_from));

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
  
        
        $string = file_get_contents($qt_file_full_path);
        $json_a = json_decode($string, true);

        
        // creo la cartella 
        $dirNameToCreate =  preg_replace('/[[:^print:]]/', '', str_replace(" ", "_", str_replace(".".$extension, "", pathinfo($fileinfo->getFilename(),PATHINFO_BASENAME))));
        if (!file_exists($qt_folder_path_to.$dirNameToCreate)) {
           if(! mkdir($qt_folder_path_to.$dirNameToCreate, 0755, true)){
               echo "ERROR in mkdir: ". $qt_folder_path_to.$dirNameToCreate. "<br/>";
           }
        }
        

        $copy_counter = 0;
        foreach($json_a['imagePathList'] as $imagePath) {
            $filefromcopyPath = $imghd_dir_path. str_replace("%20", " ", $imagePath);
            
            $filetocopyPath = $qt_folder_path_to.$dirNameToCreate.DIRECTORY_SEPARATOR. str_replace("%20", " ", str_replace("/", "_", $imagePath));
            
            if (!copy($filefromcopyPath, $filetocopyPath)) {
                echo "ERROR in copy file  ". $filefromcopyPath ." in ". $filetocopyPath . "<br/>";
            }
            $copy_counter = $copy_counter+1;
            
        }
        
        
        // move source file
        $qt_file_path_to = $qt_folder_path_to.$dirNameToCreate.DIRECTORY_SEPARATOR.preg_replace('/[[:^print:]]/', '',$fileinfo->getFilename());
        if(!rename($qt_file_full_path, $qt_file_path_to)){
            echo "ERROR move file ".$qt_file_full_path. " to " . $qt_file_path_to;
        }
        
        echo "<br/>";
        echo "Done : ".$json_a['name'].' - ' . $json_a['email'].' <br/>';
        echo "Copy n. file: ". $copy_counter."<br/><br/>";
        $counter = $counter +1;
        
    endforeach;

    echo "<br/><br/>";
    echo "Packed requests <br/>";
    echo "--------------<br/>";
    echo "DONE (".$counter.")<br/>";
    echo "--------------<br/>";
?>