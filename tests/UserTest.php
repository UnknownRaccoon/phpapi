<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UserTest extends TestCase
{
    use DatabaseTransactions;

    public function testAccontControl()
    {
        //Create user using invalid params
        $this->json('POST', '/users', [
            'username' => 'first',
            'email' => 'mrmrmrtest@gmail.com',
            'password' => '123',
        ])->see('validation_error')->see('password must be between 4 and 60')->see('role field is required');

        //Create vald user
        $array = $this->json('POST', '/users', [
            'username' => 'first',
            'email' => 'mrmrmrtest@gmail.com',
            'password' => '1234',
            'role' => 'admin',
        ])->decodeResponseJson();
        $this->assertArrayHasKey('id', $array);
        $userId = $array['id'];
        $this->seeInDatabase('users', [
            'username' => 'first',
            'email' => 'mrmrmrtest@gmail.com',
            'role' => 'admin',
        ]);

        //Create vald user with the same username & email
        $array = $this->json('POST', '/users', [
            'username' => 'first',
            'email' => 'mrmrmrtest@gmail.com',
            'password' => '1234',
            'role' => 'admin',
            'name' => 'same',
        ])->see('validation_error')->see('username has already been taken')->see('email has already been taken');
        $this->dontSeeInDatabase('users', [
            'name' => 'same',
        ]);

        //Login
        $array = $response = $this->json('POST', '/login', [
            'username' => 'first',
            'password' => '1234'
        ])->decodeResponseJson();
        $this->assertArrayHasKey('token', $array);

        //Delete user
        $this->json('DELETE', "/users/{$userId}", [], [
            'Authorization' => "Bearer {$array['token']}",
        ])->assertResponseStatus(204);
        $this->dontSeeInDatabase('users', [
            'username' => 'first',
            'email' => 'mrmrmrtest@gmail.com',
            'role' => 'admin',
        ]);
    }
    public function validRegister()
    {

    }
}
