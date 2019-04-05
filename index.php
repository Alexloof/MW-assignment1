<?php
require 'vendor/autoload.php';

function debugToConsole($msg)
{
    echo "<script>console.log(" . json_encode($msg) . ")</script>";
}

$client = new \GuzzleHttp\Client(['headers' => ['Accept' => 'application/json']]);

$singleUnicorn = (object)[];
$unicorns = [];

if (isset($_GET["unicorn"])) {
  $uri = 'http://unicorns.idioti.se/' . $_GET["unicorn"];
  $response = $client->request('GET', $uri);
  $singleUnicorn = json_decode($response->getBody());
} else {
  $response = $client->request('GET', 'http://unicorns.idioti.se/');
  $unicorns = json_decode($response->getBody());
}
debugToConsole($singleUnicorn);
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
          <form action="/">
            <div class="form-group">
              <label for="unicorn-id">Id på enhörning</label>
              <input name="unicorn" required type="number" class="form-control" id="unicorn-id">
            </div>
            <div class="form-group">
              <button type="submit" class="btn btn-success">Visa enhörning</button>
              <a  href="/" class="btn btn-primary">Visa alla enhörningar</a>
            </div>
          </form>
          <hr/>
          <?php
            if (!empty((array) $singleUnicorn)) {
              echo "<h3>$singleUnicorn->name</h3>";
              echo "<ul class=\"list-group\">";
              echo "<div class=\"row\">
                      <div class=\"col-8\">
                        <p>$singleUnicorn->spottedWhen</p>
                        <p>$singleUnicorn->description</p>
                        <strong>$singleUnicorn->reportedBy</strong>
                      </div>
                      <div class=\"col-4\">
                        <img src=\"$singleUnicorn->image\" alt=\"$singleUnicorn->name\" />
                      </div>
                    </div>";

            } else {
              echo "<h3>Alla enhörningar</h3>";
              echo "<ul class=\"list-group\">";
            
              
              foreach ($unicorns as $unicorn) {
                  echo "<li class=\"list-group-item d-flex justify-content-between align-items-center\">
                            <p>$unicorn->id $unicorn->name</p>
                          <a href=\"/?unicorn=$unicorn->id\" class=\"badge badge-primary badge-pill\" style=\"cursor: pointer; color: white\">Läs mer</a>
                        </li>";
              }
              
              echo "</ul>";
            }
          ?>
        </div>
    </body>
</html>
