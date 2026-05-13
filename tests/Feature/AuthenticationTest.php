<?php

use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertAuthenticated;
use function Pest\Laravel\get;
use function Pest\Laravel\post;

it(
    'has a route to access a login form',
    function () {
        // Act
        $response = get(route('login'));

        // Assert
        $response->assertStatus(200);
        $response->assertSeeHtml(
            '<form action="'.
            route('login.store').
            '" method="POST"'
        );
        $response->assertSee('Connexion');
        $response->assertSeeHtmlInOrder([
            '<input type="hidden" name="_token"',
            '<input type="email"',
            '<input type="password"',
            '<button type="submit"',
        ]);
    });

it(
    'redirects a successfully authenticated user to the predefined home page',
    function () {
        // Arrange
        $userData = [
            'email' => 'd@d.com',
            'password' => 'password',
            'name' => 'Dominique',
        ];
        User::create($userData);

        // Act
        $response = post(
            route('login.store'),
            $userData,
        );

        // Assert
        assertAuthenticated(config('fortify.guard'));
        $response->assertRedirect(config('fortify.home'));
    });

it(
    'redirects a guest to the login route when he tries to access an auth only route',
    function () {
        $authRoutes =
            collect(Route::getRoutes())
                ->filter(
                    fn ($route) => in_array('auth', $route->gatherMiddleware())
                );

        expect($authRoutes->count())
            ->toBeGreaterThan(0);

        foreach ($authRoutes as $route) {
            $methods = $route->methods();
            $method = strtolower($methods[0]);
            $response = $this->$method($route->uri);
            $response->assertRedirect(route('login'));
        }
    });

it(
    'displays a logout button to an authenticated user',
    function () {
        // Arrange
        actingAs(User::factory()->create());

        // Act
        $response = get(route('courses.index'));

        // Assert
        $response->assertSeeHtmlInOrder([
            '<form action="'.
            route('logout').
            '" method="POST"',
            '<input type="hidden" name="_token"',
            '<button type="submit"',
        ]);
        $response->assertSee(ucfirst(__('forms.labels-logout')));

    });

it(
    'does not display the logout button to a guest',
    function () {
        // Act
        $response = get(route('pages.home'));

        // Assert
        $response->assertDontSee('Me déconnecter');
    });

it(
    'displays a login link to a guest user on the home page',
    function () {
        $response = get(route('pages.home'));
        $response->assertSeeHtml('<a href="'.
            route('login').
            '"');
        $response->assertSee('Se connecter');

        actingAs(User::factory()->create());
        $response = get(route('pages.home'));
        $response->assertDontSeeHtml('<a href="'.
            route('login').
            '"');
        $response->assertDontSee('Se connecter');
    });
