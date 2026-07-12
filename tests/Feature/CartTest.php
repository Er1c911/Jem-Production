<?php

namespace Tests\Feature;

use Tests\TestCase;

class CartTest extends TestCase
{
    public function test_it_can_add_an_item_to_cart_and_show_it_on_cart_page(): void
    {
        $response = $this->withSession([])->post('/cart/add', [
            'id' => 'seq-demo',
            'name' => 'Demo Sequencer',
            'price' => 150000,
            'type' => 'sequencer',
        ]);

        $response->assertRedirect('/cart');
        $this->assertTrue(session()->has('cart'));
        $this->assertSame(1, count(session('cart.items')));
        $this->assertSame('Demo Sequencer', session('cart.items.0.name'));

        $cartPage = $this->get('/cart');
        $cartPage->assertOk();
        $cartPage->assertSee('Demo Sequencer');
    }
}
