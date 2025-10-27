<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ReportTest extends TestCase
{
    //testから始まるか、testアノテーションが必要
    public function testGetTodo()
    {
        $response = $this->get('api/v1/todo');
        $response->assertStatus(200);
    }

    public function testPostTodo()
    {
        $response = $this->post('api/v1/todo');
        $response->assertStatus(200);
    }

    public function testPatchTodo()
    {
        $response = $this->patch('api/v1/todo');
        $response->assertStatus(200);
    }
    
    public function testDeleteTodo()
    {
        $response = $this->delete('api/v1/todo');
        $response->assertStatus(200);
    }
}

  
