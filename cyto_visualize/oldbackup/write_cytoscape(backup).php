<?php
    // GET THE FILE IDENTIFIER
    $random_string=trim($_GET['My_kda']);
    // $random_string="M3uxS7BRXo";
?>

    <!-- GET THE COLORS OF THE MODULES TO CREATE THE KEY AT THE BOTTOM -->
<?php
    $color_list = "<table><tr>";
    $module_list = "<tr>";
    $colors="/home/www/abhatta3-webserver/Data/Pipeline/Results/cytoscape/$random_string.wKDA_cytoscape_module_color_mapping.txt";
    $color_data = file_get_contents($colors);
    $color_explode = explode("\n", $color_data);
    for ($i=1;$i<(count($color_explode)-1);$i++)
    {
        $color_sep = explode("\t", $color_explode[$i]);
        $module_number = $color_sep[0];
        $module_color = $color_sep[1];
        $color_list .= "<td id=\"circle\" style=\"background-color:$module_color\"></td>";
        $module_list .= "<td> Module: $module_number </td>";
    }
    $color_list .= "</tr>";
    $module_list .= "</tr></table>";
    // TO MAKE IT READABLE
    // $color_list = htmlspecialchars("$color_list");
    // $module_list = htmlspecialchars("$module_list");
    // echo "$color_list";
    // echo "$module_list";
?>

    <!-- GET EDGE NAME, SOURCE, TARGET, AND WEIGHT -->
<?php
    $edge_list = "edges: [ ";
    $edge_weight = "";
    $edges="/home/www/abhatta3-webserver/Data/Pipeline/Results/cytoscape/$random_string.wKDA_cytoscape_edges.txt";
    $edge_data = file_get_contents($edges);
    $edge_explode = explode("\n", $edge_data);
    for ($i=1;$i<(count($edge_explode)-1);$i++)
    {
        $edge_sep= explode("\t", $edge_explode[$i]);
        $head_node = $edge_sep[1];
        $tail_node = $edge_sep[0];
        $weight = $edge_sep[2];
        $edge_list .= "{ id: \"$head_node-$tail_node\", target: \"$head_node\", source: \"$tail_node\", weight: $weight }, \n";
        $edge_weight .= "{ attrValue: \"$head_node-$tail_node\", value: $weight }, \n";
    }
    $edge_list = substr("$edge_list", 0, -3);
    $edge_list .= " ]\n";
    $edge_weight = substr("$edge_weight", 0, -3);
    $edge_weight .= " ]";
    // echo "$edge_weight";
?>

<?php
    $node_list = "nodes: [ ";
    $node_colors = "";
    $node_shapes = "";
    $node_size = "";
    $url_list = "";
    $nodes="/home/www/abhatta3-webserver/Data/Pipeline/Results/cytoscape/$random_string.wKDA_cytoscape_nodes.txt";
    $node_data = file_get_contents($nodes);
    $node_explode = explode("\n", $node_data);
    for ($i=1;$i<(count($node_explode)-1);$i++)
    {
        $node_sep= explode("\t", $node_explode[$i]);
        $node = $node_sep[1];
        $size = $node_sep[3];
        $shape = $node_sep[4];
        $color = $node_sep[2];
        $url = $node_sep[6];

        $node_list .= "{ id: \"$node\", label: \"$node\" }, \n";
        $node_colors .= "{ attrValue: \"$node\", value: \"$color\" }, \n";
        $node_shapes .= "{ attrValue: \"$node\", value: \"$shape\" }, \n";
        // added to make grey nodes small
        if (trim($color) == "#909090")
        {
        $node_size .= "{ attrValue: \"$node\", value: 10 }, \n";    
        }
        else{
        $node_size .= "{ attrValue: \"$node\", value: $size }, \n";
        }
        // end changes
        if ($url != ""){
            $url = trim($url);

            $url_list .= "{ attrValue: \"$node\", value: \"http://mergeomics.research.idre.ucla.edu/cyto_visualize/Images_To_Upload/"."$random_string"."_Image_$i\" }, \n";

            $content = file_get_contents("$url");
            //Store in the filesystem.
            $fp = fopen("/home/www/abhatta3-webserver/cyto_visualize/Images_To_Upload/"."$random_string"."_Image_"."$i", "w");
            fwrite($fp, $content);
            fclose($fp);
            chmod("/home/www/abhatta3-webserver/cyto_visualize/Images_To_Upload/"."$random_string"."_Image_"."$i", 0777);
        }
    }
    
    $node_list = substr("$node_list", 0, -3);
    $node_list .= " ],";
    $node_colors = substr("$node_colors", 0, -3);
    $node_colors .= " ]";
    $node_shapes = substr("$node_shapes", 0, -3);
    $node_shapes .= " ]";
    $node_size = substr("$node_size", 0, -3);
    $url_list = substr("$url_list", 0, -3);
    // echo "$node_colors";

    $testing = $node_list.",\n".$edge_list;


    $fpathparam="/home/www/abhatta3-webserver/Data/Pipeline/Resources/kda_temp/$random_string"."KDAPARAM";
    $kdaparam = file_get_contents($fpathparam);

    $pieces = explode("direction <- ",$kdaparam);

    if(trim($pieces[1]) == "1"){
        $directed = "false";
    }

    if(trim($pieces[1]) == "2"){
        $directed = "true";
    }

?>


<!-- WRITE TO FILE -->
<?php
    // $text1 = file_get_contents("/home/www/abhatta3-webserver/cyto_visualize/cyto_text_1.txt");
    $text1_1 = file_get_contents("/home/www/abhatta3-webserver/cyto_visualize/cyto_text_1.1.txt");
    $text1_2 = file_get_contents("/home/www/abhatta3-webserver/cyto_visualize/cyto_text_1.2.txt");
    $text2 = file_get_contents("/home/www/abhatta3-webserver/cyto_visualize/cyto_text_2.txt");
    $text3 = file_get_contents("/home/www/abhatta3-webserver/cyto_visualize/cyto_text_3.txt");
    $text4 = file_get_contents("/home/www/abhatta3-webserver/cyto_visualize/cyto_text_4.txt");
    $text5 = file_get_contents("/home/www/abhatta3-webserver/cyto_visualize/cyto_text_5.txt");
    $text6 = file_get_contents("/home/www/abhatta3-webserver/cyto_visualize/cyto_text_6.txt");
    $text7 = file_get_contents("/home/www/abhatta3-webserver/cyto_visualize/cyto_text_7.txt");
    $text8 = file_get_contents("/home/www/abhatta3-webserver/cyto_visualize/cyto_text_8.txt");
    $file_path = fopen("/home/www/abhatta3-webserver/cyto_visualize/cytoscape_network_"."$random_string".".php", "w");
    // fwrite($file_path, $text1);
    fwrite($file_path, $text1_1);
    fwrite($file_path, $directed);
    fwrite($file_path, $text1_2);
    fwrite($file_path, $node_list);
    fwrite($file_path, $edge_list);
    fwrite($file_path, $text2);
    fwrite($file_path, $node_shapes);
    fwrite($file_path, $text3);
    fwrite($file_path, $url_list);
    fwrite($file_path, $text4);
    fwrite($file_path, $node_size);
    fwrite($file_path, $text5);
    fwrite($file_path, $node_colors);
    fwrite($file_path, $text6);
    fwrite($file_path, $edge_weight);
    fwrite($file_path, $text7);
    fwrite($file_path, $color_list);
    fwrite($file_path, $module_list);
    fwrite($file_path, $text8);
    fclose($file_path);
    chmod("/home/www/abhatta3-webserver/cyto_visualize/cytoscape_network_"."$random_string".".php", 0777);
    header('Location: '."/cyto_visualize/cytoscape_network_"."$random_string".".php"); /* Redirect browser */
?>