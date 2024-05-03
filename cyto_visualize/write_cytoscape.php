<?php
    // GET THE SESSION ID
    $sessionID=trim($_GET['sessionID']);
    // $sessionID="M3uxS7BRXo";

// GET THE COLORS OF THE MODULES TO CREATE THE TABLE AT THE BOTTOM OF SIDEBAR -->
    $ROOT_DIR = $_SERVER['DOCUMENT_ROOT'] . "/";
    if(!file_exists($ROOT_DIR."cyto_visualize/cytoscape_network_"."$sessionID".".php")){

        $module_list = "";
        $colors=$ROOT_DIR . "Data/Pipeline/Results/cytoscape/$sessionID.wKDA_cytoscape_module_color_mapping.txt";
        $color_data = file_get_contents($colors);
        $color_explode = explode("\n", $color_data);
        for ($i=1;$i<(count($color_explode)-1);$i++)
        {
            $color_sep = explode("\t", $color_explode[$i]);
            $module_number = $color_sep[1];
            $module_color = $color_sep[2];
            $module_list .= "<tr><td class=\"circle\" style=\"background-color:$module_color\"></td><td> $module_number </td></tr>";
        }

    //GET EDGE NAME, SOURCE, TARGET, AND WEIGHT -->
        $edge_list = "edges: [ ";
        $edge_weight = "";
        $edges=$ROOT_DIR . "Data/Pipeline/Results/cytoscape/$sessionID.wKDA_cytoscape_edges.txt";
        $edge_data = file_get_contents($edges);
        $edge_explode = explode("\n", $edge_data);
        for ($i=1;$i<(count($edge_explode)-1);$i++)
        {
            $edge_sep= explode("\t", $edge_explode[$i]);
            $head_node = $edge_sep[1];
            $tail_node = $edge_sep[0];
            $weight = $edge_sep[2];
            $edge_list .= "{data: { id: \"$head_node-$tail_node\", target: \"$head_node\", source: \"$tail_node\", weight: $weight }}, \n";
            
        }
        $edge_list = substr("$edge_list", 0, -3);
        $edge_list .= " ]\n";


        $node_list = "nodes: [ ";
        $node_colors = "";
        $node_shapes = "";
        $node_size = "";
        $url_list = "";
        $nodes=$ROOT_DIR . "Data/Pipeline/Results/cytoscape/$sessionID.wKDA_cytoscape_nodes.txt";
        $node_data = file_get_contents($nodes);
        $node_explode = explode("\n", $node_data);
        for ($i=1;$i<(count($node_explode)-1);$i++)
        {
            $node_sep= explode("\t", $node_explode[$i]);
            $node = $node_sep[1];
            $size = $node_sep[3];
            $shape = strtolower($node_sep[4]);
            $color = $node_sep[2];
            $url = $node_sep[6];
            $border = $node_sep[10];

            if (trim($color) == "#cfcfcf" && $url != "") //never occurs?
            {
                $url = trim($url);

                $content = file_get_contents("$url");
                //Store in the filesystem.
                $fp = fopen($ROOT_DIR."cyto_visualize/Images_To_Upload/"."$sessionID"."_Image_"."$i", "w");
                fwrite($fp, $content);
                fclose($fp);
                chmod($ROOT_DIR ."cyto_visualize/Images_To_Upload/"."$sessionID"."_Image_"."$i", 0777);

                $node_list .= "{data: { id: \"$node\", type: \"$shape\", color: \"$color\", background: \"/cyto_visualize/Images_To_Upload/"."$sessionID"."_Image_$i\", size: \"10\" }}, \n";
            }
            else if(trim($color) != "#cfcfcf" && $url != "") // occurs
            {
                $url = trim($url);

                $content = file_get_contents("$url");
                //Store in the filesystem.
                $fp = fopen($ROOT_DIR . "cyto_visualize/Images_To_Upload/"."$sessionID"."_Image_"."$i", "w");
                fwrite($fp, $content);
                fclose($fp);
                chmod($ROOT_DIR . "cyto_visualize/Images_To_Upload/"."$sessionID"."_Image_"."$i", 0777);
                $valign = "center";
                if(trim($shape) == "diamond"){
                    $fontsize="40px";
                    $node_list .= "{data: { id: \"$node\", type: \"$shape\", color: \"$color\", fontsize: \"$fontsize\", valign: \"$valign\", background: \"/cyto_visualize/Images_To_Upload/"."$sessionID"."_Image_$i\", size: \"$size\", border: \"$border\" }}, \n";
                } else {
                    $fontsize="30px";
                    $node_list .= "{data: { id: \"$node\", type: \"$shape\", color: \"$color\", fontsize: \"$fontsize\", valign: \"$valign\", background: \"/cyto_visualize/Images_To_Upload/"."$sessionID"."_Image_$i\", size: \"$size\" }}, \n";
                }
            }
            else if(trim($shape) == "diamond" ) // added by Jess, make KDs big
            {
                $valign = "center";
                $fontsize="40px";
                $node_list .= "{data: { id: \"$node\", type: \"$shape\", color: \"$color\", fontsize: \"$fontsize\", valign: \"$valign\", size: \"$size\", border: \"$border\" }}, \n";
            }
            else if(trim($color) == "#cfcfcf" && $url == "") //occurs
            {
                $valign = "top";
                $fontsize="30px";
                $node_list .= "{data: { id: \"$node\", type: \"$shape\", color: \"$color\", fontsize: \"$fontsize\", valign: \"$valign\", size: \"20\" }}, \n";
            }
            else if(trim($color) != "#cfcfcf" && $url == "") //never occurs? no url is always #909090
            {
                $node_list .= "{data: { id: \"$node\", type: \"$shape\", color: \"$color\", size: \"$size\" }}, \n";
            }

        
        
        

        }
    
        $node_list = substr("$node_list", 0, -3);
        $node_list .= " ],";
        // echo "$node_colors";

    





        // WRITE TO FILE
        $text1 = file_get_contents("./make_file/cyto_text1.txt");
        $text2 = file_get_contents("./make_file/cyto_text2.txt");
        $text3 = file_get_contents("./make_file/cyto_text3.txt");

        $file_path = fopen($ROOT_DIR . "cyto_visualize/cytoscape_network_"."$sessionID".".php", "w");
        // fwrite($file_path, $text1);
        fwrite($file_path, $text1);
        fwrite($file_path, $module_list);
        fwrite($file_path, $text2);
        fwrite($file_path, $node_list);
        fwrite($file_path, $edge_list);
        fwrite($file_path, $text3);

        fclose($file_path);
        chmod($ROOT_DIR . "cyto_visualize/cytoscape_network_"."$sessionID".".php", 0777);
        echo json_encode("cytoscape_network_".$sessionID.".php?sessionID=".$sessionID);
    }else{
        echo json_encode("cytoscape_network_".$sessionID.".php?sessionID=".$sessionID);
    }

    //header('Location: '.); /* Redirect browser */
?>