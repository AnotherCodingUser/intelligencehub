<?php 
    include 'header.html';
    $AUTHTOKEN = 42;

    
    ########## Board ID ##########
    $board_id = "611c153ad4ece869eb5b0dec"; #DEA GoI Trello ID
        
    ########## LISTS ##########
    $list_array = [
        "Allied" => "611c154b69d6128b1d1c40e7",
        "Raids" => "611c1552f500351e71547b43",
        "Blacklist" => "611c155da75d6415b13c184b",
        "Archived" => "611c1566cbf64161e17d2e9e",
        "Ongoing Dicussions" => "611c163856767f2d24da83b7",

    ];

    ########## Trello Auth ##########
    $T_KEY = "";
    $T_TOKEN = "";
?>

<form action="results.php" method="get">
<input type="hidden" name="id" value="<?php echo $AUTHTOKEN ?>">
<select id="list" name="list" size="4">
    <?php
    foreach ($list_array as $value) {
        $key = array_search($value, $list_array);
        echo "<option value='$value'>$key</option>";
    }
        
    ?>

</select>
<input type="Submit">
</form>



<?php
    include 'footer.html';
?>