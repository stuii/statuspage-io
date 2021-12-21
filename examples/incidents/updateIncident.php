<?php
    require '../../vendor/autoload.php';

    $dotenv = Dotenv\Dotenv::createImmutable('../../');
    $dotenv->load();

    $client = new \Stui\StatuspageIo\Client(
        apiKey: $_ENV['API_KEY'],
        pageId: $_ENV['PAGE_ID']
    );

    $incident = new \Stui\StatuspageIo\Service\Incident($client);
    $incident->setId($_ENV['INCIDENT_ID']);
    $incident->getIncident();


    $incident->setIncidentStatus(
        new \Stui\StatuspageIo\Enums\IncidentStatus(
            \Stui\StatuspageIo\Enums\IncidentStatus::IDENTIFIED
        )
    );

    try {
        var_dump($incident->updateIncident());
    } catch (\Stui\StatuspageIo\Exceptions\StatuspageException $e) {
        // handle exception

        echo $e->getMessage();
    }
