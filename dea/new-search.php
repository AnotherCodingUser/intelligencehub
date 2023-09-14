<?php 


    ############ Board ID ###########
    $board_id = "611c153ad4ece869eb5b0dec"; #DEA GoI Trello ID
        
    ############# LISTS #############
    $allied_id = "611c154b69d6128b1d1c40e7"; #Allied GoI's
    $raid_id = "611c1552f500351e71547b43"; #Raiding Party's
    $blacklist_id = "611c155da75d6415b13c184b"; #Blacklisted GoI's
    $archive_id = "611c1566cbf64161e17d2e9e"; #Archieved GoI's
    $ongoing_id = "611c163856767f2d24da83b7"; #Ongoing Dicussion GoI

    ########## Trello Auth ##########
    $T_KEY = "";
    $T_TOKEN = "";

    ############# Vars ##############
    $evidence = "WIP";



    function apirequest($url) {
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $resp = curl_exec($curl);
        curl_close($curl);

        $resp_data = json_decode($resp);
        return $resp_data;

    }

    function containsquery($str) {
        if (ctype_space($str) || $str == '') {
			return false;
		}
		else{
			return true;
		}
    }

    function dropdowns ($id) {
        global $T_KEY, $T_TOKEN;
        $temp_array = array();
        $fields = apirequest("https://api.trello.com/1/customFields/$id/options?key=$T_KEY&token=$T_TOKEN");
        foreach ($fields as $field) {
            $temp_array[$field->_id] = array("color"=>$field->color,"text"=>$field->value->text);
        } 
        return $temp_array;
    }

    function allied ($input){
        if (empty($input)) {
            return "TBD";
        }
        else {
            return $input;
        }
    }
    
    function get_string_between($string, $start, $end){
        $string = ' ' . $string;
        $ini = strpos($string, $start);
        if ($ini == 0) return '';
        $ini += strlen($start);
        $len = strpos($string, $end, $ini) - $ini;
        return substr($string, $ini, $len);
    }
    ?>

<?php
    $cards_array = array();
    if ($_GET['list'] != "all"){
        $list_id = $_GET['list'];
        $url = "https://api.trello.com/1/lists/$list_id/cards?key=$T_KEY&token=$T_TOKEN&fields=desc,name,idList&customFieldItems=true";

    }
    else
    {
        $url = "https://api.trello.com/1/boards/$board_id/cards/visible?&fields=desc,name,idList&customFieldItems=true&key=$T_KEY&token=$T_TOKEN";
        
    }
    $card_list = apirequest($url);
    $goi_classes = dropdowns("611c17bb13d8e430cb63b09a");
    $status_classes = dropdowns("611c16f4eeb01802df3d58f8");
    
    
    foreach ($card_list as $card) {
        $notes = Null;
        if( (strpos($card->name, "GoI #") !== false)) {
            foreach ($card->customFieldItems as $field) {   
                $content = "";
                $value = $field->value;
                if (!empty($value)) {
                    if ($field->idCustomField == "611c16624b395e14aa394b4d") {
                        $leader = $value->text;
                    }
                    if ($field->idCustomField == "611c193ec97173326780e593") {
                        $grouplink = $value->text;
                    }
                    if ($field->idCustomField == "611c168464e5fc38d5559b42") {
                        $ambassador = [$value->text,"<b>Ambassador:</b>  $value->text <br>"];
                    }
                    if ($field->idCustomField == "611c16a48c767d8b25a13c87") {
                        $attache = [$value->text, "<b>Attaché:</b> $value->text <br>"];
                    }
                    if ($field->idCustomField == "611c1949b00f4461a93e037b") {
                        $contract = [$value->text, "<b><a href='$value->text'>Contract Link</a></b>"];
                    }
                    if ($field->idCustomField == "611d1400420597199b2e256e") {
                        $notes = [$value->text, "<b>Notes:</b> $value->text"];
                    }
                }
                if ($field->idCustomField == "611c17bb13d8e430cb63b09a") {
                    $goi_field_id = $field->idValue;
                    $goi_type = $goi_classes[$goi_field_id]["text"];
                    $goi_colour = $goi_classes[$goi_field_id]["color"];
                }
                if ($field->idCustomField == "611c16f4eeb01802df3d58f8") {
                    $staus_field_id = $field->idValue;
                    $status = $status_classes[$staus_field_id]["text"];
                    $status_colour = $status_classes[$staus_field_id]["color"];
                }
                
            }
            if (($card->idList == $allied_id) or ($card->idList == $raid_id)){
                $contentfields = [$ambassador, $attache, $contract];
                $a_bool = ($ambassador != NULL && containsquery($ambassador)) ;
                $b_bool = ($attache != NULL && containsquery($attache));
                $c_bool = ($contract != NULL && containsquery($contract));
                if (($a_bool xor $b_bool) xor (($a_bool xor $c_bool) xor $b_bool)){
                    if ($a_bool == false) {
                        $ambassador = allied($ambassador);
                        $ambassador = [$ambassador,"<b>Ambassador:</b>  $ambassador <br>"];
                    }
                    if ($b_bool == false) {
                        $attache = allied($attache);
                        $attache = [$attache, "<b>Attaché:</b> $attache <br>"];
                    }
                    if ($c_bool == false) {
                        $contract = allied($contract);
                        $contract = [$contract, "<b><a href='$contract'>Contract Link</a></b>"];
                    }
                }
                foreach ($contentfields as $contentfield) {
                    try {
                        $content = "$content $contentfield[1] \n";
                    }
                    catch (Exception $e){
                        $content = "Error \n";
                    }
                    
                }
            }

            $cardID = $card->id;
            $title = $card->name;
            $desc = $card->desc;
            if ($card->idList == $blacklist_id){
                $issuer = get_string_between($desc, '***Issued by: ', '***');
                $expiry = get_string_between($desc, '***Expiring on: ', '***');
                $reasoning = get_string_between($desc, '```', '```');
                $reasoning = str_replace('-', '<br>-', $reasoning);

                $content = "<b>Issued By:</b>  $issuer <br>
                <b>Expiring on:</b> $expiry <br>
                <b>Evidence:</b> $evidence";
                $footer = "<b>Reason:</b> $reasoning";
            
                
            }
            else{
                if (isset($notes)){
                    $footer = $notes[1];
                }
                else{
                    $footer = null;
                }
                
            }            
            if (isset($content)== false){
                $content = null;
            }
            
            array_push($cards_array,array($title,$content,$footer,$cardID,$leader,$grouplink));
            
        }
        else{

        }

    
    }
   
    foreach ($cards_array as $card)
    {
        if ($card[5]== "N/A"){
            $link = $card[0];
        }
        else {
            $link = "<a href='$card[5]'>$card[0]</a>";
        }
        if (($card[1]== "") || ($card[1] == Null)) {
            $content = Null;
        }
        else{
            $content = $card[1];
        }
        if ($content == Null){
            $content = "";
        }
        else{
            $content = "<section>
                            <div>
                                $content
                            </div>
                        </section>";
        }
        if ($footer == Null){
            $footer = "";
        }
        else{
            $footer = " <footer>
                            $card[2]
                        </footer>";
        }
        if ($card[0] != "GoI #000"){
            echo "
            <div class='card'>
                <header>
                    <div class='cardtitle' style='text-align:left;'>$link<br><b>Owner: </b>$card[4]</div>
                    <div>
                        <a href='nowhere.php?id=$card[3]'><i class='fas fa-edit'></i></a>
                    </div>
                    
                </header>
                $content
                $footer
            </div>
            ";
        }
        
    }
        
    
?>


    
