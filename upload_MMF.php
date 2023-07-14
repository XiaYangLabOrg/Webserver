<?php
//**This upload page is used to upload a Marker Mapping File. This page is shared with all 4 pipelines (MDF, MSEA, META, wKDA)
/*
1) User uploads the file on the frontend. 
2) The file is then stored temporarily to check if the headers are correct (1st line) and if data is found (2nd line)
3) Saves the file onto the server



*/

 if (isset($_FILES['MMFuploadedfile']['name'])) //checks if the file that was uploaded exists
 {
 	if(isset($_POST['rmchoice']) ? $_POST['rmchoice'] : null) //checks which pipeline the user is uploading from
    {
        $rmchoice = $_POST['rmchoice'];
        if($rmchoice == 1) // == MDF pipeline
        {
            $target_path = "./Data/Pipeline/Resources/ldprune_temp/";
        }
        else if($rmchoice == 2) // == MSEA pipeline 
        {
            $target_path = "./Data/Pipeline/Resources/msea_temp/";
        }
        else if($rmchoice == 3) // == META pipeline
        {
            $target_path = "./Data/Pipeline/Resources/meta_temp/";
        }
        else // == wKDA pipeline
        {
            $target_path = "./Data/Pipeline/Resources/kda_temp/";
        }
    }
    



    $target_path = $target_path . basename($_FILES['MMFuploadedfile']['name']); //target path to save file on server
    $filename =  basename($_FILES['MMFuploadedfile']['name']); //retrieve the uploaded filename
    $fh = fopen($_FILES['MMFuploadedfile']['tmp_name'], 'r'); //open the uploaded file to start the check
    $index=0; //index number for the loop (ie how many times the loop has ran)
 

    if ($fh) //check if the file was opened correctly
    {
            while ( $index++ < 2 ) //run the loop twice
            {
                $line = fgets( $fh ); //read each line individually
                $check = "GENE\tMARKER";
                if ($line !== false && $index == 1) 
                {
                    if (strstr($line, $check))
                    {
                        echo '1'; //Header is correct ('1')
                                                
                    }
                    else
                    {
                        echo '0'; //Header is not correct ('0')
                        fclose($fh);
                        exit;
                    }

                } 
                else if($line == false && $index == 2)
                {
                    echo '0'; //Header is correct but no second line ('10')
                    fclose($fh);
                    exit;
                }
                else if($line == true && $index == 2)
                {
                    if(preg_match('/\S/', $line))
                    {
                        echo "1"; //Header is correct and secondline does have data ('11')
                    }
                    else
                    {
                        echo  "0"; //Header is correct, but secondline does have data ('10')
                        fclose($fh);
                        exit;
                    }
                    
                }
                else
                {
                    die('No data/empty file: '.basename($_FILES['MMFuploadedfile']['name'])); //Empty file. End script and do not continue.
                }

                

            }

           
    } 
    else 
    {
    // error opening the file.
        die('Could not open file: '. basename($_FILES['MMFuploadedfile']['name']));
    } 


    if($_FILES['MMFuploadedfile']['tmp_name'])
    {

        if(move_uploaded_file($_FILES['MMFuploadedfile']['tmp_name'], $target_path))
         {

                    echo "1"; //Header is correct, secondline does have data, and uploaded correctly ('111')

        } 
      
    }  

    if (fclose($fh) === false) { //close the file
        die('Could not close file: '.basename($_FILES['MMFuploadedfile']['name']));
        }
 
 }

?>