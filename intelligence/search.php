<?php 
    include 'header.html';
    

    function apirequest($url) {
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $resp = curl_exec($curl);
        curl_close($curl);

        $user_data = json_decode($resp);
        return $user_data;

    }

?>
<div class="search">


<?php

    if( $_GET["id"] == 42 ){
        if ($_GET["search_type"] == "user" ) {
            $user_ID = $_GET["qry"];
            $url = "https://users.roblox.com/v1/users/$user_ID";
            $data = apirequest($url);
            echo "  <ul>
                        <li>
                            Display name: $data->displayName
                        </li>
                        <li>
                            Username: <a href='https://users.roblox.com/v1/users/$user_ID'></a>$data->name
                        </li>
                        <li>    
                            Roblox ID: $user_ID
                        </li>
                    </ul>";
        }
        if ($_GET["search_type"] == "group" ) {
            $group_ID = $_GET["qry"];
            $url = "https://groups.roblox.com/v1/groups/$group_ID";
            $data = apirequest($url);
            $user_data = $data->owner;
            echo "  <ul>
                        <li>
                           Group name: <a href='https://groups.roblox.com/v1/groups/$group_ID'>$data->name</a>
                        </li>
                        <li>
                            Owner: <a href='https://users.roblox.com/v1/users/$user_data->userId'>$user_data->username</a>
                        </li>
                        <li>    
                            Group ID: $group_ID
                        </li>
                        <li>    
                            Member count: $data->memberCount
                        </li>
                    </ul>";
        }
    

?>
    
</div>

    

<?php
    }
    else {
        header("Location: ../404.php", true, 301);
    exit();
    }
    include 'footer.html';
?>