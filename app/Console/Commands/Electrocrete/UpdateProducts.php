<?php

namespace App\Console\Commands\Electrocrete;

use App\Console\Commands\ReservationDay;
use App\Console\Commands\Source;
use Illuminate\Console\Command;

use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;

use App\Models\Scrapers\Proxy;

class UpdateProducts extends Command{

    protected $signature = 'electrocrete:update-products';
    protected $description = 'Update Electrocrete products';

	public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $steps = 10;
        $this->output->progressStart($steps);
        # make request to
        $targetUrl = 'https://menfashion.gr';
        $this->output->progressAdvance();

        # Proxy
        $proxy = Proxy::getOldestLastUsed();
        $this->output->progressAdvance();

        $client = new Client([
            RequestOptions::PROXY => $proxy->port,
            RequestOptions::VERIFY => false, # disable SSL certificate validation
            RequestOptions::TIMEOUT => 30, # timeout of 30 seconds
        ]);
        $this->output->progressAdvance();

        try {
            $body = $client->get($targetUrl)->getBody();
            echo $body->getContents();
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
        $this->output->progressAdvance();

        $this->output->progressFinish();
	}
}
