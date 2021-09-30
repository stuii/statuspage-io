<?php
    require '../../vendor/autoload.php';

    $dotenv = Dotenv\Dotenv::createImmutable('../../');
    $dotenv->load();

    $client = new \Stui\StatuspageIo\Client();
    $client->setApiKey($_ENV['API_KEY']);
    $client->setPageId($_ENV['PAGE_ID']);

    $incident = new \Stui\StatuspageIo\Service\Incident($client, 'Test-Incident');
    $incident->setBody('Lorem ipsum dolor sit amet, consetetur sadipscing elitr');
    $incident->addComponentId($_ENV['COMPONENT_ID']);
    $incident->addAffectedComponents(
        componentId: $_ENV['COMPONENT_ID'],
        componentStatus: new \Stui\StatuspageIo\Enums\ComponentStatus(
            \Stui\StatuspageIo\Enums\ComponentStatus::MAJOR_OUTAGE
        )
    );

    $incident->setImpactOverride(
        new \Stui\StatuspageIo\Enums\Impact(
            \Stui\StatuspageIo\Enums\Impact::CRITICAL
        )
    );
    $incident->setIncidentStatus(
        new \Stui\StatuspageIo\Enums\IncidentStatus(
            \Stui\StatuspageIo\Enums\IncidentStatus::INVESTIGATING
        )
    );

    try {
        $incident->createNew();
    } catch (\Stui\StatuspageIo\Exceptions\StatuspageException $e){
        // handle exception

        echo $e->getMessage();
    }