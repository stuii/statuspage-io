<?php
    require '../../vendor/autoload.php';

    $dotenv = Dotenv\Dotenv::createImmutable('../../');
    $dotenv->load();

    $client = new \Stui\StatuspageIo\Client(
        apiKey: $_ENV['API_KEY'],
        pageId: $_ENV['PAGE_ID']
    );

    $incident = new \Stui\StatuspageIo\Service\Incident($client, 'Test-Maintenance');
    $incident->setBody('Lorem ipsum dolor sit amet, consetetur sadipscing elitr');
    $incident->addComponentId($_ENV['COMPONENT_ID']);

    try {
        $incident->scheduleMaintenance(
            from: new DateTime('2025-09-01 23:00:00',new DateTimeZone('Europe/Zurich')),
            to: new DateTime('2025-09-01 23:01:00',new DateTimeZone('Europe/Zurich'))
        );
    } catch (\Stui\StatuspageIo\Exceptions\StatuspageException $e){
        // handle exception

        echo $e->getMessage();
    }