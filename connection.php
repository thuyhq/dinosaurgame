<?php
    $connection = new MongoClient(); // connects to localhost:27017
    $connection = new MongoClient( "mongodb://example.com" ); // connect to a remote host (default port: 27017)
    $connection = new MongoClient( "mongodb://example.com:65432" ); // connect to a remote host at a given port
?>