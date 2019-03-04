<?php

namespace ApiMate; 

use GuzzleHttp\Client as GClient;

class Client {
  protected $client;
  protected $host;
  
  public $ping;

  protected $resources = [];

  public function __construct($host, $options) {
    $this->host = trim($host, "/");
    $this->options = $options;
    $this->client = new GClient([
      'base_uri' => $this->host,
      'timeout' => 30.0
    ]);
  }

  protected function _make_request($method, $ep, $data) {
    $opts = [];
    if ($this->options['headers']) {
      $opts['headers'] = $this->options['headers'];
    }

    if (strtolower($method) === 'get' && $data !== null) {
      $opts['query'] = $data;
    }

    $resp = $this->client->request($method, $ep, $opts);

    if ($resp->getStatusCode() !== 200) {
      throw new \Error('Resource Error');
    }

    $body = $resp->getBody();
    $payload = $body->getContents();

    return json_decode($payload);
  }

  public function get($endpoint, $params=null) {
    return $this->_make_request('GET', $endpoint, $params); 
  }
}
