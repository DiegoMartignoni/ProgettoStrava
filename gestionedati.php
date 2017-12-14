<?php
$nomecookie = 'visita';
if (!isset($_COOKIE[$nomecookie])) {

    $value = 1;
    setcookie($nomecookie, $value, time() + 3600 * 24 * 7);
}
//parte la session
session_start();
$arrayat = $_SESSION['arrayatleta'];

$_SESSION['accesstoken'] = $arrayat['access_token'];

//var_dump($arrayat['access_token']);
?>
<!DOCTYPE html>

<html>
    <head>
        <!--Librerie Bootstrap e Jquery -->
        <meta charset="utf-8"> 
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <title> Gestione  </title>
        <style> 
            .bgimg {
                /*background-image: url('http://www.montagnaestate.it/wp-content/uploads/offerte-vacanze-montagna-estate-890x490.jpg');
                 background-size:contain;
                 background-repeat:no-repeat;*/
                width : 100%;
                height : 100%; 
                border : 1px solid blue;

            }
            .col-sm-4{

                /*     div.hideContent{display:none;}
                     div.showContent{display:block;}
                */}
            .col-lg-8 {
                float : right;
            }
            .die_ {
                position : relative; 

                /*  top: 50%;
                  left: 40%;*/ 
                background: #3498db;
                background-image: -webkit-linear-gradient(top, #3498db, #2980b9);
                background-image: -moz-linear-gradient(top, #3498db, #2980b9);
                background-image: -ms-linear-gradient(top, #3498db, #2980b9);
                background-image: -o-linear-gradient(top, #3498db, #2980b9);
                background-image: linear-gradient(to bottom, #3498db, #2980b9);
                -webkit-border-radius: 28;
                -moz-border-radius: 28;
                border-radius: 28px;
                font-family: Arial;
                color: #ffffff;
                font-size: 20px;
                padding: 10px 20px 10px 20px;
                text-decoration: none;
                .die_:hover {
                    background: #3cb0fd;
                    background-image: -webkit-linear-gradient(top, #3cb0fd, #3498db);
                    background-image: -moz-linear-gradient(top, #3cb0fd, #3498db);
                    background-image: -ms-linear-gradient(top, #3cb0fd, #3498db);
                    background-image: -o-linear-gradient(top, #3cb0fd, #3498db);
                    background-image: linear-gradient(to bottom, #3cb0fd, #3498db);
                    text-decoration: none;
                }

                /*margin : auto;
                width : 100 px;*/

            }
        </style>    

    </head>
    <body style="background-color : beige;">
        <div id="main" class="container-fluid">
            <?php
            $nome = $arrayat['athlete']['firstname'];
            $cognome = $arrayat['athlete']['lastname'];
            if ($arrayat['athlete']['sex'] == 'M')
                $sesso = 'maschile';
            else if ($arrayat['athlete']['sex'] == 'F')
                $sesso = 'femminile';
            echo ("L'atleta si chiama <b> $nome $cognome </b>  <br> ");
            echo ("Vive a <b> " . $arrayat['athlete']['city'] . "</b>  In regione <b>" . $arrayat['athlete']['state'] . "</b> Ed è di sesso " . $sesso);
            ?>




            <script type="text/javascript" >

                var arraydati = null;
                function cercaatleta(stat) {
                    var idatleta = <?php echo json_encode($arrayat['athlete']['id']); ?>;
                    var access_token = <?php echo json_encode($arrayat['access_token']); ?>;
                    var url = "https://www.strava.com/api/v3/athletes/" + idatleta + "/" + stat;


                    $.ajax({
                        type: 'GET',
                        dataType: 'json',
                        url: url,
                        async: true,
                        headers: {
                            "Authorization": "Bearer " + access_token

                        },
                        //   data : { }


                        success: function (data) {
                            // alert('eseguito');
                            // console.log(data);

                            var text = JSON.stringify(data, null, 4);

                            //  console.log(data);
                            if ($("#jsonkm").length > 0)
                            {
                                $("#jsonkm").replaceWith("<pre id='jsonkm'>" + text + "</pre>");
                            } else {
                                $("#jsonkm").append("<pre id='jsonkm'>" + text + "</pre>");
                            }
                            //utlizzo questa funzione per poter "Globalizzare" il json ricevuto                    

                            getDati(data, stat);
                        },
                        error: function (data) {
                            //var jsonr = $.parseJSON(data);
                            //   var returnedData = JSON.parse(data);
                            alert('Fallito');
                            console.log(data, stat);
                            //  var returnedData = JSON.parse(response);


                        }
                    });

                }
                function getDati(data, stat) {

                    arraydati = data;


                    //if (arraydati.biggest_ride_distance !== 'undefined' && arraydati.biggest_ride_distance !== null) {
                    if (stat === 'stats') {
                        // console.log(document.getElementById("fo1"));
                        if (document.getElementById("fo1") === null) {
                            var maxdistance = parseInt(arraydati.biggest_ride_distance);
                            maxdistance = maxdistance / 1000;
                            jQuery('<h5>', {
                                id: 'fo1',
                                // href: '',
                                // title: '',
                                // rel: '',
                                text: 'Il tuo giro più lungo è di ' + maxdistance + ' Km '

                            }).appendTo('#datk');


                            $('#foo').append("<br>");

                            var totdist = Math.round(parseInt(arraydati.all_ride_totals.distance) / 1000);
                            jQuery('<h5>', {
                                id: 'fo2',
                                // href: '',
                                // title: '',
                                // rel: '',
                                text: 'Da quando hai strava hai compiuto una distanza di ' + totdist + ' Km in bicicletta'

                            }).appendTo('#datk');

                            // $("#jsonkm").append(text);
                        }
                    } else if (stat === 'koms') {
                        console.log(arraydati[0].name);
//  for (var name in arraydati) {
                        //    console.log(name);

                        //}
                        var secondi;

                        for (var i = 0; i < arraydati.length; i++)
                        {
                            secondi = normalizzasec(arraydati[i].moving_time);
                            minuti = parseInt(arraydati[i].moving_time / 60);
                            console.log(minuti);
                            console.log(secondi);
                            document.getElementById("tbl").innerHTML += "<tr><td>" + arraydati[i].name + "</td><td>" + minuti + ":" + secondi + "  Minuti</td></tr>"

                        }

                    }
                }
                function normalizzasec(tempo) {
                    var seconditot = parseInt(tempo);
                    var intminuti = parseInt(seconditot / 60);
                    var floatminuti = seconditot / 60;
                    var secondi = (floatminuti % 1) * 60;
                    return secondi.toFixed(0);
                }



                function upload() {


                    // document.getElementById("datk").innerHTML =
                    document.write("<form enctype='multipart/form-data' action='http://mazzolenisimone.altervista.org/ProgettoStrava/gestionedati.php' method='POST'>"
                            //   + "<input type='hidden' name='MAX_FILE_SIZE' value='50000' />"
                            + "<label for='file' class='btn btn-default btn-file'> "
                            + "Scegli file: <input type='file' name='filetraccia' id='filetraccia'/> "
                            + "<input type='submit' value='Carica File'/></label></form>");


                }
            </script>
        </div>

        <div class="row">

            <div id="maxkm" class="col-lg-3" style="background-color:white;">
                <button type="button" class="btn btn-info" data-toggle="collapse" data-target="#esp">Premi per vedere il Json</button>
                <div id="esp" class="collapse">
                    <pre id="jsonkm"></pre> 
                </div>
            </div>
            <div id="datk" class="col-lg-5" style="background-color:navajowhite;">


            </div>

            <div id="datj" class="col-lg-8" style="background-color:wheat;">
                <table id='tbl' class='table'>
                    <caption> Lista dei segmenti in cui hai il KOM </caption>
                    <thead class="thead-inverse">
                        <tr><th>Nome</th><th>Tempo impiegato </th></tr>

                    </thead>

                </table>

            </div>
            <!--  <div class="col-sm-4" style="background-color:lavenderblush;">.col-sm-4</div>
              <div class="col-sm-4" style="background-color:lavender;">.col-sm-4</div> -->

        </div>


        <div class="navbar navbar-fixed-bottom" style=" border : 1px solid blue; background-color:wheat;" >
            <center>
                <button type="button" class="die_"  onclick="cercaatleta('stats')"> Premi per cercare informazioni atleta </button>
                <button type="button" class="die_" onclick="cercaatleta('koms')"> Premi per visionare i kom </button>
                <button type="button" class="die_" onclick="upload()"> Premi per Caricare un file </button>
                <?php
                $nomefile = 'filetraccia';
                //se è caricato
                if (isset($_FILES[$nomefile])) {

                    $file_size = $_FILES[$nomefile]['size'];

                    //se il file è maggiore di 0,9 mb non lo carico
                    if ($file_size < '900000') {
                        //tipo application/octet-stream
                        $tipofile = $_FILES[$nomefile]['type'];
                        if (!is_uploaded_file($_FILES[$nomefile]['tmp_name'])) {
                            echo 'File non  caricato';
                            exit;
                        }

                        /* echo ("<script>if ($('#fo1').length > 0)
                          {
                          $('#fo1').replaceWith(<p> Il file " . $_FILES['tracciagpx'] . " è stato caricato</p>);
                          } else {
                          $('#fo1').append(<p> Il file " . $_FILES['tracciagpx'] . " è stato caricato</p>);
                          }</script>"); */

                        //move_uploaded_file($nomefile, '/Upload')) 
                        //percorso della cartella dove mettere i file caricati dagli utenti
                        $uploaddir = 'Upload/';
                        $file_name = $_FILES[$nomefile]['name'];

                        $file_tmp = $_FILES[$nomefile]['tmp_name'];

                        if (move_uploaded_file($file_tmp, $uploaddir . $file_name)) {

                            $formato = explode('.', $file_name);
                            $formato = $formato[1];
                            echo "Il file " . $_FILES[$nomefile]['name'] . " è stato appena caricato sul server ftp";
                            //inizio l'uploading su strava
                            $ch = curl_init();
                            //set del array field
                            $fields = [
                                'activity_type' => "ride",
                                // 'file' => new CURLFile($uploaddir, $tipofile),
                                'file' => "@Upload/$file_name",
                                'data_type' => $formato,
                            ];

                            //   var_dump($fields);

                            curl_setopt($ch, CURLOPT_URL, "https://www.strava.com/api/v3/uploads");
                            curl_setopt($ch, CURLOPT_POST, true);
                            curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                            $headers = [
                                'Authorization: Bearer ' . $_SESSION['accesstoken'],
                                'Content-Type: Content-Type:multipart/form-data'
                            ];
                           
                            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                             echo (" header :" . curl_getinfo($ch,CURLINFO_REQUEST_SIZE ) . "<br><br>");
                            $response = curl_exec($ch);
                            $status = curl_getinfo($ch);
                            if ($status != 200) {
                                echo("Status :  " . var_dump($status) . " response del server<b> : $response </b>");
                                // "header " . var_dump($headers) . " Campi post : " . var_dump($fields) .
                            }
                            curl_close($ch);
                            //   var_dump($server_output);
                        }
                    } else {

                        echo 'Upload NON valido!';
                    }
                    /*
                      //$ftp_host = 'ftp.mazzolenisimone.altervista.org';
                      // $ftp_user_name = 'mazzolenisimone';
                      //  $ftp_user_pass = 'ehvolevi';
                      $file = $_FILES[$nomefile];
                      $connect_it = ftp_connect($ftp_host);
                      $login_result = ftp_login($connect_it, $ftp_user_name, $ftp_user_pass);
                      if (ftp_get($connect_it, $local_file, $remote_file, FTP_BINARY)) {
                      } else {
                      }
                      ftp_close($connect_it); */
                } else {

                    echo "<p id='statfil'>Nessun file in upload</p>";
                }
                //   define("uploddir", "./Upload/");
                ?>

            </center>

        </div>

    </body>
</html>