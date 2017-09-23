<?php



// Buyer login in TelecomsXChange
$api_login ="ENTER YOUR BUYER USERNAME HERE";

//Your API key (Get one on www.telecomsxchange.com/ )

$api_key = "ENTER YOUR API KEY"; 


// initialising CURL

$ch = curl_init();

//controller is a script name, so in case lookup.php controller is lookup
$controller = "lookup";

//unix timestamp to ensure that signature will be valid temporary
$ts = time();

//compose signature concatenating controller api_key api_login and unix timestamp
$signature = hash( 'sha256', $controller .  $api_key   . $api_login  . $ts);

$search = $_REQUEST['search'];
$searchform = (int) $_REQUEST['searchform'];
$type = $_REQUEST['type'];

$params = array(
                'ts' => $ts,  //Provide TS
                'signature' => $signature,
                'webapi' => '1',   //required field by tcxc api
                'api_login' => $api_login,
                'searchform' => '1'  //required field
                );

if($searchform) {

        $params['type'] = $type;

        if(preg_match("/^(\d+)/", $search, $matches)) {
                $params['prefix'] = $matches[1];
        } else {
                $params['country'] = $search;
        }

        //query against api. URL
        curl_setopt($ch, CURLOPT_URL,"https://members.telecomsxchange.com/$controller.php");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,
        http_build_query($params));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $server_output = curl_exec ($ch);
        $result = json_decode($server_output, true);
        $rates = $result['rates'];

        curl_close ($ch);

}

?>

<html>
        <head>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css" integrity="sha384-/Y6pD6FV/Vv2HJnA6t+vslU6fwYXjCFtcEpHbNJ0lyAFsXTsjBbfaDjzALeQsN6M" crossorigin="anonymous">
        <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

       <!-- Getting JQUERY for autocomplete to work in the search box -->
          
        <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>


<script>
$().ready(function() {


    $( "#search" ).autocomplete({
      source: "get_zoom_list.php"
    });



  });
  </script>


        </head>

        <body>

        <div class="jumbotron">

                <form action="index.php">
                <input type=hidden name=searchform value=1>
                <div class="input-group">
                  <input type="text" id=search name=search class="form-control" placeholder="Enter country code or country name" aria-describedby="basic-addon1" value="<?php echo "$search";?>" >

                  <select name='type' class="form-control">
                        <option value='ANY' <?php if($type == 'ANY') echo "SELECTED";?> >ANY</option>
                        <option value='NA'  <?php if($type == 'NA') echo "SELECTED";?>  >NA</option>
                        <option value='CLI' <?php if($type == 'CLI') echo "SELECTED";?> >CLI</option>
                        <option value='TDM' <?php if($type == 'TDM') echo "SELECTED";?>  >TDM</option>
                        <option value='NoCLI' <?php if($type == 'NoCLI') echo "SELECTED";?> >NoCLI</option>
                  </select>

                  <input type="submit" class="form-control" value="search">
                </div>
                </form>

        </div>

        <?php
          
          // This code will utilize the Lookup API to get the High/Low market rates from TelecomsXChange backend api
                if($searchform)

                        if(count($rates)) {
                                $min_price_n = 100000.0 ;
                                $min_interval_1 = 1;
                                $min_interval_n = 1;

                                $max_price_n = 0.0;
                                $max_interval_1 = 1;
                                $max_interval_n = 1;

                                foreach($rates as $rate) {
                                        if($min_price_n > $rate['price_n']) {
                                                $min_price_n = $rate['price_n'];
                                                $min_interval_1 = $rate['interval_1'];
                                                $min_interval_n = $rate['interval_n'];
                                        }

                                        if($max_price_n < $rate['price_n']) {
                                                $max_price_n = $rate['price_n'];
                                                $max_interval_1 = $rate['interval_1'];
                                                $max_interval_n = $rate['interval_n'];
                                        }
                                }

                          // Show results if rates were found
                          
                                echo "<div class='alert alert-success' role='alert'>Highest Price: $max_price_n [$max_interval_1.$max_interval_n]    |    Lowest Price: $min_price_n [$min_interval_1.$min_interval_n]</div>";



                        } else {
                          
                          // When no results found show some error
                                echo "<div class='alert alert-danger' role='alert'>No results found</div>";
                        }
        ?>


        </body>
</html>
