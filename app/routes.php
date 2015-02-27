<?php

use Respect\Rest\Router;
use Thapp\XmlBuilder\XmlBuilder;
use Thapp\XmlBuilder\Normalizer;
use BadShows\Helpers\Input;
use BadShows\Helpers\Response;
use BadShows\Helpers\Validation;
use BadShows\Repositories\ShowsRepository;

// Helper Classes
$input = new Input();
$response = new Response($basePath);
$validation = new Validation();

// Repositories
$showsRepository = new ShowsRepository($basePath);

// Routing
$route = new Router('/index.php/');

// Main view
$route->get('/', function () use ($response) {
    return $response->make("main");
});

// List shows
$route->get('/shows/', function () use ($response, $showsRepository) {
    $shows = $showsRepository->all();
    return $response->send($shows);
});

// Fetch an XML representation
$route->get('/shows-xml', function () use ($response, $showsRepository) {
    $shows = $showsRepository->all();
    $data = [];

    foreach ($shows as $show) {
        $data['program'][] = [
            'date' => $show['date'],
            'start_time' => $show['start_time'],
            'lead_text' => $show['lead_text'],
            'name' => $show['name'],
            'b-line' => $show['bline'],
            'synopsis' => $show['synopsis'],
            'url' => $show['url'],
        ];
    }

    $xmlBuilder = new XMLBuilder('programs');
    $xmlBuilder->load($data);
    $xml = $xmlBuilder->createXML(true);

    $response->send($xml, 200, [['Content-type' => 'text/xml']]);
});

// Find by id
$route->get('/shows/*', function ($id) use ($response, $showsRepository) {
    $show = $showsRepository->find($id);
    if (!empty($show)) {
        return $response->send($show);
    }
    return $response->send("Could not find show with id {$id}", 404);
});

// Add a new show
$route->post('/shows/', function () use ($input, $validation, $response, $showsRepository) {

    $fields = $input->all();

    $rules = [
        "lead_text" => ["required"],
        "name" => ["required"],
        "start_time" => ["required"],
        "bline" => ["required"],
        "synopsis" => ["required"]
    ];

    if ($validation->run($fields, $rules) === false) {
        return $response->send($validation->getErrorMessages(), 400);
    }

    // Add today's date, as there's no now support in SQLite
    $fields['start_time'] = date("H:i", strtotime($fields['start_time']));
    $fields['date'] = date("Y-m-d H:i:s");

    if ($show = $showsRepository->create($fields)) {
        return $response->send($show, 200);
    }

    return $response->send("Unknown error, ask you nearest cat", 500);
});

// Delete a show
$route->delete('/shows/*', function ($id) use ($response, $showsRepository) {
    if ($showsRepository->delete($id)) {
        return $response->send("Show deleted", 200);
    }
    return $response->send("Could not find show with id {$id}", 404);
});


