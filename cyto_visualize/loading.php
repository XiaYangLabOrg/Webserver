<?php
    $sessionID=trim($_GET['sessionID']); 
?>
<html dir="ltr" lang="en-US">
<head>
<link rel="stylesheet" href="/include/style.css?v=05022024" type="text/css"/>
<link rel="stylesheet" href="/include/running_animation.css?v=05022024" type="text/css"/>
<script type="text/javascript" src="/include/js/jquery.js"></script>
</head>
<body class="stretched">
    <div class="loading-window">
        <div class="DNA_cont">
            <div class="nucleobase"></div>
            <div class="nucleobase"></div>
            <div class="nucleobase"></div>
            <div class="nucleobase"></div>
            <div class="nucleobase"></div>
            <div class="nucleobase"></div>
            <div class="nucleobase"></div>
            <div class="nucleobase"></div>
            <div class="nucleobase"></div>
            <div class="nucleobase"></div>
        </div>

        <div class="text">
            <span>Loading KDA subnetwork</span><span class="dots">...</span>
    </div>

    <script type="text/javascript">
        var sessionID="<?php echo $sessionID;?>";
        $.ajax({
        url: "/cyto_visualize/wrtie_cytoscape.php",
        global: false, type: "GET", 
        data: ({"sessionID":sessionID}), 
        cache: false,
        success: function(html) {
            window.location = "/cyto_visualize/cytoscape_network_"+sessionID+".php?sessionID="+sessionID;
        }
        });
    </script>
</body>
</html>