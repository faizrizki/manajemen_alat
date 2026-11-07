<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    public function test_halaman_utama_dialihkan_ke_dasbor_aset(): void
    {
        $this->get('/')->assertRedirect('/aset');
    }
}
