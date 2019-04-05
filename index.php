<?php
require 'vendor/autoload.php';

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

$log = new Logger('Assignment 1');
$log->pushHandler(new StreamHandler('logs.log', Logger::INFO));

$client = new \GuzzleHttp\Client(['headers' => ['Accept' => 'application/json']]);
$singleUnicorn = (object)[];
$unicorns = [];
$errorMessage = '';

if (isset($_GET["unicorn"])) {
  $uri = 'http://unicorns.idioti.se/' . $_GET["unicorn"];
  try {
    $response = $client->request('GET', $uri);
    $singleUnicorn = json_decode($response->getBody());
    $log->info('Requested info about' . $singleUnicorn->name);
  } catch(Exception $e) {
    $log->error($e->getMessage());
    $errorMessage = "We could not find the unicorn you were searching for...";
  }
  
} else {
  try {
    $response = $client->request('GET', 'http://unicorns.idioti.se/');
    $unicorns = json_decode($response->getBody());
    $log->info('Requested info about alla unicorns');
  } catch (Exception $e) {
    $log->error($e->getMessage());
    $errorMessage = "We could not load the unicorns...";
  }
}
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
            if ($errorMessage) {
              echo "<h4>$errorMessage<h4/>";
              exit();
            }

            if (!empty((array) $singleUnicorn)) {
              $formattedDate = date_format(date_create($singleUnicorn->spottedWhen),"Y/m/d");

              echo "<h3>$singleUnicorn->name</h3>";
              echo "<ul class=\"list-group\">";
              echo "<div class=\"row\">
                      <div class=\"col-8\">
                        <p>$formattedDate</p>
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
