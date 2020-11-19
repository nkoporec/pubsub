<?php

namespace App\Console\Commands;

use GuzzleHttp\Utils;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Redis;

class TrackingCommand extends Command
{
    /**
     * Command name.
     *
     * @var string
     */
    protected $signature = 'track:accounts {--account_id=}';

    /**
     * Command description.
     *
     * @var string
     */
    protected $description = 'Tails the redis server for accounts.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            $this->info("Tracking events ...");
            $this->getMessages();
        } catch (\Exception $e) {
            // Redis will throw an exception if connection fails.
            $this->error("Can't connect to Redis, re-trying in 5 seconds ...");
            sleep(5);
            $this->handle();
        }
    }

    /**
     * Get redis messages.
     */
    public function getMessages()
    {
        return Redis::subscribe(["tracking-server"], function ($message) {
            $data = Utils::jsonDecode($message);

            // Check if we need to filter.
            $accountId = $this->option('account_id') ? $this->option('account_id') : false;
            if ($accountId) {
                if ($accountId == $data->account_id) {
                    $this->displayMessage($data);
                }
                return;
            }

            $this->displayMessage($data);
        });
    }

    /**
     * Display the message in the cli.
     *
     * @param object $data
     *    Data returned from Redis.
     *
     */
    public function displayMessage($data)
    {
        $response = "[$data->timestamp] [$data->account_id] [$data->account_name] $data->data";
        echo $this->info($response);
    }
}
