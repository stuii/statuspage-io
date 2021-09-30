<?php
    require '../../vendor/autoload.php';

    $dotenv = Dotenv\Dotenv::createImmutable('../../');
    $dotenv->load();

    $client = new \Stui\StatuspageIo\Client();
    $client->setApiKey($_ENV['API_KEY']);
    $client->setPageId($_ENV['PAGE_ID']);

    $incident = new \Stui\StatuspageIo\Service\Incident($client, 'Test-Backfill');
    $incident->setBody('Lorem ipsum dolor sit amet, consetetur sadipscing elitr');

    /*
     * When backfilling an incident, affected components cannot be specified.
     */

    try {
        $incident->backfillIncident(
            new DateTime('2021-08-01 12:00:00', new DateTimeZone('Europe/Zurich'))
        );
    } catch (\Stui\StatuspageIo\Exceptions\StatuspageException $e){
        // handle exception

        echo $e->getMessage();
    }