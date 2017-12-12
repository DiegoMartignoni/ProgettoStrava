
<!DOCTYPE html>
<html>
    <head>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title> Prova api strava</title>
        <style> 

        </style>
    </head>
    <body>


    <center>
        <div id='cosedafare' style='align-content: middle'>

            <h2> Scegliere l'opzione e avviare </h2>
            <form  class="form-horizontal" action="" method="" onchange="camb()" >
                <input  type="radio" name="scope" value="public" > <b>public </B> (default, private activities are not returned, privacy zones are respected in stream requests)<br>
                <input type="radio" name="scope" value="write" > <b>write </b> (	modify activities, upload on the user’s behalf)<br>
                <input type="radio" name="scope" value="view_private" ><b> view_private</b> (view private activities and data within privacy zones)<br>
                <input type="radio" name="scope" value="view_private,write"  checked> <b>view_private,write</b> (both ‘view_private’ and ‘write’ access)<br>
            </form><br> <br>
            <script>
                function camb() {
                    var clientid = 21709;
                    var uri_red = 'http://mazzolenisimone.altervista.org/ProgettoStrava';
                    var scope = document.querySelector('input[name="scope"]:checked').value;
                    var state = 'prova';
                    var hrefvalue = ("https://www.strava.com/oauth/authorize?client_id=" + clientid + "&response_type=code&redirect_uri=" + uri_red + "&scope=" + scope + "&state=" + state + "&approval_prompt=force")

                    $("#acl").attr("href", hrefvalue);

                }

            </script>
            <a id='acl' href='' onclick="camb()"> Clicca qui per iniziare </a><br> <br>
        </div>
        <?php
        $clientid = 21709;
        $publictoken = '7df4fe08602d6519c5df1851d19fc8b508f78bcb';
        $clientsecret = file_get_contents("../secret.txt");
        var_dump($clientsecret);
        $uri_red = 'http://mazzolenisimone.altervista.org/ProgettoStrava';
        $stato = 'provapi';
        ?>



        <?php
        $code = filter_input(INPUT_GET, 'code');
        $state = filter_input(INPUT_GET, 'state');
        if (isset($code)) {

            session_start();

            echo ("<script> $('#acl').remove(); </script> ");


            $endpoint = "https://www.strava.com/oauth/token";
            $params = array('client_id' => $clientid,
                'client_secret' => $clientsecret,
                'code' => $code);

            $curl = curl_init($endpoint);
            curl_setopt($curl, CURLOPT_HEADER, true);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_HEADER, 'Content-Type: application/json');

            $postData = "";

            foreach ($params as $k => $v) {
                $postData .= $k . '=' . urlencode($v) . '&';
            }
            $postData = rtrim($postData, '&');
            curl_setopt($curl, CURLOPT_POSTFIELDS, $postData);


            $json_response = curl_exec($curl);

            $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            if ($status != 200) {
                echo("Errore:  $endpoint  Status:  $status, response del server : $json_response, curl_error " . curl_error($curl) . ", Numero errore: " . curl_errno($curl) . "\n");
            }
            curl_close($curl);

            $_SESSION['clientid'] = $clientid;
            $_SESSION['publictoken'] = $publictoken;
            $_SESSION['clientsecret'] = $clientid;
            // var_dump($json_response);
            $arrayatleta = json_decode($json_response, true);
            var_dump($arrayatleta);
            $_SESSION['arrayatleta'] = $arrayatleta;

            //echo $arrayatleta;
            header("Location : gestionedati.php");
        }


        /*
          echo ("<script> $.ajax(
          {
          type: 'POST',
          dataType: 'json',
          url: 'https://www.strava.com/oauth/token',

          data: {client_id: $clientid,  client_secret: '$clientsecret' ,code : '$code'
          },

          success: function (data) {


          alert('eseguito');
          var json = $.parseJSON(data);
          alert(json.html);

          },
          error: function (data) {

          alert('Fallito');
          var json = $.parseJSON(data);
          alert(json.error);
          }
          }
          ); </script>"); */
        ?>
    </center>

</div>
<script>



    function richiesta() {
        $.get(
                //         "https://www.strava.com/oauth/authorize?",
                //     {client_id: myid, response_type: "code", redirect_uri: "http://mazzolenisimone.altervista.org", scope: "view_private",
                //           state: "mystate", approval_prompt: "force"},
                //   function (data) {
                //     alert('page content: ' + data);


                //        }
                );
    }


</script>
</body>
</html>