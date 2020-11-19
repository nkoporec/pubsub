<?php

use App\Models\Account;

class AccountTest extends TestCase
{
    /** @test */
    public function it_has_account_endpoints()
    {
        // Assert missing account id.
        $failed_response = $this->post('/account/');

        $failed_response->assertResponseStatus(404);

        // Assert not found account.
        $failed_response = $this->post('/account/32323223323');

        $failed_response->assertResponseStatus(200);
        $failed_response->seeJsonEquals([
            'success' => false,
            'error' => "Account not found.",
        ]);

        // Assert a valid account.
        $acc = Account::factory()->create([
            'name' => "Nejc",
            'active' => 1,
        ]);
        $valid_response = $this->post('/account/' . $acc->id);
        $valid_response->assertResponseStatus(200);
        $valid_response->seeJsonEquals([
            'success' => true,
            'error' => "Data broadcasted successfully.",
        ]);
    }
}
