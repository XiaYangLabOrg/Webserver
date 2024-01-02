<?php
    // GET THE SESSION ID
    $sessionID=trim($_GET['sessionID']);
    $drugname=trim($_GET['drugres']);
    // $sessionID="M3uxS7BRXo";
?>

    <!-- GET THE COLORS OF THE MODULES TO CREATE THE TABLE AT THE BOTTOM OF SIDEBAR -->
<?php

    $module_list = "<tr><td class=\"circle\" style=\"background-color:#F56A79\"></td><td> Input gene and drug gene overlap </td></tr>
                     <tr><td class=\"circle\" style=\"background-color:#51ADCF\"></td><td> Drug gene first neighbor from input gene </td></tr>
                     <tr><td class=\"circle\" style=\"background-color:#E0DEDE\"></td><td> Input gene </td></tr>";
    
?>

    <!-- GET EDGE NAME, SOURCE, TARGET, AND WEIGHT -->
<?php
    $edge_list = "edges: [ ";
    $edges="/home/www/abhatta3-webserver/Data/Pipeline/Resources/shinyapp2_temp/$sessionID"."/$drugname"."_cytoscape_edges.txt";
    $edge_data = file_get_contents($edges);
    $edge_explode = explode("\n", $edge_data);
    for ($i=1;$i<(count($edge_explode)-1);$i++)
    {
        $edge_sep= explode("\t", $edge_explode[$i]);
        $head_node = $edge_sep[0];
        $tail_node = $edge_sep[1];
        $weight = $edge_sep[2];
        $edge_list .= "{data: { id: \"$head_node-$tail_node\", target: \"$head_node\", source: \"$tail_node\", weight: $weight }}, \n";
        
    }
    $edge_list = substr("$edge_list", 0, -3);
    $edge_list .= " ]\n";

?>

<?php
    $node_list = "nodes: [ ";
    $node_colors = "";
    $node_shapes = "";
    $node_size = "";
    $url_list = "";
    $nodes="/home/www/abhatta3-webserver/Data/Pipeline/Resources/shinyapp2_temp/$sessionID"."/$drugname"."_cytoscape_nodes.txt";
    $node_data = file_get_contents($nodes);
    $node_explode = explode("\n", $node_data);
    for ($i=1;$i<(count($node_explode)-1);$i++)
    {
        $node_sep= explode("\t", $node_explode[$i]);
        $node = $node_sep[0];
        $size = $node_sep[2];
        $shape = strtolower($node_sep[3]);
        $color = $node_sep[1];
        $node_list .= "{data: { id: \"$node\", type: \"$shape\", color: \"$color\", size: \"$size\" }}, \n"; 
    }
    
    $node_list = substr("$node_list", 0, -3);
    $node_list .= " ],";
    // echo "$node_colors";
   
?>


<!-- WRITE TO FILE -->
<?php

    $text1 = file_get_contents("./make_file/cyto_app2_text1.txt");
    $text2 = file_get_contents("./make_file/cyto_text2.txt");
    $text3 = file_get_contents("./make_file/cyto_app2_text3.txt");

    $file_path = fopen("/home/www/abhatta3-webserver/cyto_visualize/"."$sessionID"."_"."$drugname"."_cytoscape_network.php", "w");
    // fwrite($file_path, $text1);
    fwrite($file_path, $text1);
    fwrite($file_path, $module_list);
    fwrite($file_path, $text2);
    fwrite($file_path, $node_list);
    fwrite($file_path, $edge_list);
    fwrite($file_path, $text3);

    fclose($file_path);
    chmod("/home/www/abhatta3-webserver/cyto_visualize/"."$sessionID"."_"."$drugname"."_cytoscape_network.php", 0777);
    header('Location: '."/cyto_visualize/"."$sessionID"."_"."$drugname"."_cytoscape_network.php?sessionID=$sessionID"); /* Redirect browser */
?>

