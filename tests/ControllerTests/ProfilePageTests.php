<?php

namespace App\Tests\ControllerTests;

use App\Repository\UserRepository;
use App\Tests\BaseTestCaseSetUp;
use Exception;

class ProfilePageTests extends BaseTestCaseSetUp
{
    /**
     * @throws Exception
     */
    public function testRenderProfilePage(): void {
        $userRepository = static::getContainer()->get(UserRepository::class);

        // retrieve the test user
        $testUser = $userRepository->findOneByEmail('spulska.tv@gmail.com');

        // simulate $testUser being logged in
        $this->client->loginUser($testUser);

        // test e.g. the profile page
        $this->client->request('GET', '/my_profile');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h2', 'Profile');
    }

    /**
     * @throws Exception
     */
    public function testRouteUserSettings(): void {
        $userRepository = static::getContainer()->get(UserRepository::class);

        // retrieve the test user
        $testUser = $userRepository->findOneByEmail('spulska.tv@gmail.com');

        // simulate $testUser being logged in
        $this->client->loginUser($testUser);

        $this->client->request('GET', '/profile_settings');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h2', 'Update Your Data');
    }
}