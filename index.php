<?php
$cache_time = (5 * 60) * 60;
$cache_folder = 'cache/';
$cache_filename = $cache_folder . md5($_SERVER['REQUEST_URI']); // Location to lookup or store cached file  

$cache_created  = (file_exists($cache_filename)) ? filemtime($cache_filename) : 0;

if ((time() - $cache_created) < $cache_time) {
    print str_replace(array("\n", "\r", "\t"), '', file_get_contents($cache_filename)); // The cached copy is still valid, read it into the output buffer  
    exit();
}

ob_start();

file_put_contents("stations.json", file_get_contents("https://open.fm/radio/api/v2/ofm/stations_slug.json" . "?" . time()));

$stations_json = json_decode(file_get_contents('stations.json'));
$channels_json = $stations_json->channels;
$stations = "";

foreach ($channels_json as $channel) {
    $stations .= '<div class="card mt-4 ml-4 mx-auto" style="max-width: 220px;">';
    $stations .= '<img class="mx-auto" height="150" src="' . str_replace("71x71", "150x150", str_replace("http://", "https://", $channel->logo->url)) . '" alt="">';
    $stations .= '<div class="card-body">';
    $stations .= '<span class="badge badge-success">WSPIERANE</span>';
    $stations .= '<h4 class="card-title">' . $channel->name . '</h4>';
    $stations .= '<p class="card-text"><a href="https://stream.open.fm/' . $channel->id . '">https://stream.open.fm/' . $channel->id . '</a></p>';
    $stations .= '</div>';
    $stations .= '</div>';
    //$bbcode .= '[*][img]' . str_replace("71x71", "12x12", $channel->logo->url) . '[/img] [b]' . $channel->name . '[/b] - https://stream.open.fm/' . $channel->id . PHP_EOL;
}

//$bbcode = "[list]" . PHP_EOL;
//$bbcode .= "[/list]";
//var_dump(file_put_contents('bbcode.txt', $bbcode));
?>
<!doctype html>
<html lang="pl">

<head>
    <title>Stacje muzyczne | ts3.style - Stylowe miejsce pogawędek</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="shortcut icon" href="favicon.png" />

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <style>
        body {
            background-color: #141414;
            background-image: url(https://ts3.style/img/az-subtle.png);
        }

        .card {
            background-color: #1c1c1c;
            padding: 10px;
            border: 1px solid #1c1c1c;
        }

        .card-title {
            color: #e7e7e7;
        }

        h1 {
            color: #e7e7e7;
            text-shadow: 0px 0px 5px #000;
        }

        .logo {
            animation: heartbeat 2s infinite;
        }

        @keyframes heartbeat {
            0% {
                transform: scale(0.75);
            }

            20% {
                transform: scale(1);
            }

            40% {
                transform: scale(0.75);
            }

            60% {
                transform: scale(1);
            }

            80% {
                transform: scale(0.75);
            }

            100% {
                transform: scale(0.75);
            }
        }
    </style>
</head>

<body>

    <div class="container mt-4 mb-3">
        <button id="toggle_images" type="button" class="btn btn-secondary">Ukryj Obrazki</button>
        <div class="row">
            <a class="mx-auto" href="https://ts3.style"><img class="img-fluid logo" src="logo_ts3style.svg" /></a>
        </div>
        <!-- <a href="muzobot-stacje-bbcode.zip" type="button" class="btn btn-info">Pobierz w BBCODE (lista na serwer)</a> -->
        <hr />
        <h1><strong>OPEN.FM</strong></h1>
        <hr />
        <div class="row">
            <?php echo $stations ?>
        </div>
        <hr />
        <h1><strong>POZOSTAŁE</strong></h1>
        <hr />
        <div class="row">
            <div class="card mt-4 ml-4 mx-auto" style="max-width: 220px;">
                <img class="mx-auto" height="150" src="https://static.radio.pl/images/broadcasts/8f/69/21823/c300.png" alt="Radio Party - Kanał Główny" />
                <div class="card-body">
                    <h4 class="card-title">Radio Party - Kanał Główny</h4>
                    <p class="card-text"><a href="http://s4.radioparty.pl:8005/">http://s4.radioparty.pl:8005/</a></p>
                </div>
            </div>
        </div>
        <span class="text-muted">Zakaz kopiowania, © ts3.style</span>
    </div>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script>
        $('#toggle_images').on('click', function() {
            $('img').toggle();
        });
    </script>
</body>

</html>

<?php

file_put_contents($cache_filename, ob_get_contents());
unlink("stations.json");
ob_end_flush();
?>