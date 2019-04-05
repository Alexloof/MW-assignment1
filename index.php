<?php
require 'vendor/autoload.php';

function debugToConsole($msg)
{
    echo "<script>console.log(" . json_encode($msg) . ")</script>";
}

$client = new \GuzzleHttp\Client(['headers' => ['Accept' => 'application/json']]);

if (isset($_GET["unicorn"])) {
  debugToConsole($_GET["unicorn"]);
  $uri = 'http://unicorns.idioti.se/' . $_GET["unicorn"];
  $response = $client->request('GET', $uri);
} else {
  $response = $client->request('GET', 'http://unicorns.idioti.se/');
}



$unicorns = json_decode($response->getBody());

?>

<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Enhörningar</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    </head>
    <body>
        <div class="container">
          <h1>Enhörningar</h1>
          <hr/>
          <form action="index.php">
            <div class="form-group">
              <label for="unicorn-id">Id på enhörning</label>
              <input name="unicorn" type="number" class="form-control" id="unicorn-id">
            </div>
            <div class="form-group">
              <button type="submit" class="btn btn-success">Visa enhörning</button>
              <button type="submit" class="btn btn-primary">Visa alla enhörningar</button>
            </div>
          </form>
          <hr/>
          <?php
            echo "<h3>Alla enhörningar</h3>";
            echo "<ul class=\"list-group\">";
          
            
            foreach ($unicorns as $unicorn) {
                echo "<li class=\"list-group-item d-flex justify-content-between align-items-center\">
                          <p>$unicorn->id $unicorn->name</p>
                        <span class=\"badge badge-primary badge-pill\">Läs mer</span>
                      </li>";
            }
            
            echo "</ul>";
          ?>
        </div>
    </body>
</html>
