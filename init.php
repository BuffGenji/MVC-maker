<?php
/**
 * Here will be the initialisation script, from here we will create the  project structure with
 * our php script from another project and we will write the path to a common file.
 * 
 * The FileWriter service will reference this GLBALLY DEFINED CONSTANT
 */

declare(strict_types=1);

 namespace App;
 
 function suggestFolders($sourceDir): void
 {
     $folders = ['Controllers', 'Models', 'Views', 'Config'];
     echo "Would you like to create the following folders in $sourceDir? (y/n)\n";
     foreach($folders as $folder) {
         echo "$folder\n";
     }   
     echo "Create all folders? (y/n)\n";
 
     if (getNextLine() === 'y') {
         foreach ($folders as $folder) {
             mkdir("./$sourceDir/" . $folder, 0777, true);
         }
     } else {
         foreach ($folders as $folder) {
             if (!file_exists('./'.$sourceDir.'/'.$folder)) {
                 echo "$folder? (y/n)";
                 if (trim(fgets(STDIN)) === 'y') {
                     mkdir("./$sourceDir/" . $folder, 0777, true);
                 }
             }
         }
     }
 }
 
 function getNextLine()
 {
     return trim(fgets(STDIN));
 }
 
 function createSourceDirectory()
 {
     echo "Would you like a custom directory name? (y/n)\n";
     if (getNextLine() === 'y') {
         echo "Enter the name of the directory, if directory name is alread used, 'src' will be tried\n";
         $input = getNextLine();
         if ($input !== "q") {
             if (!file_exists($input)) {
                 mkdir($input, 0777, true);
                 echo "Directory $input created\n";
                 return $input;
             } elseif (!file_exists('src')) {
                 mkdir('src', 0777, true);
             }
         }
     }
 }
 
 while (true) {
     echo "Would you like to create a new project? (y/n)\n";
     getNextLine() == 'y' ?: exit;
     $sourceDir = createSourceDirectory();
     define('SOURCE_DIR_PATH', __DIR__ ."\\" . $sourceDir);
     suggestFolders($sourceDir);
     touch($sourceDir . '/index.php');
     file_put_contents($sourceDir . '/index.php', "<?php\n\nnamespace $sourceDir;\n\n");
     echo "Are you done? (y/n)";
     if (getNextLine() === 'y') {
         exit;
     }
 }
 