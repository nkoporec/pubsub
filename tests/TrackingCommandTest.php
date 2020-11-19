
<?php

class TrackingCommandTest extends TestCase
{
    /** @test */
    public function it_has_tracking_command()
    {
        $this->assertTrue(class_exists(\App\Console\Commands\TrackingCommand::class));
    }
}
