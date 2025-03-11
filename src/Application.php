<?php

declare(strict_types=1);


namespace App;

use Cake\Core\Configure;
use Cake\Core\ContainerInterface;
use Cake\Datasource\FactoryLocator;
use Cake\Error\Middleware\ErrorHandlerMiddleware;
use Cake\Http\BaseApplication;
use Cake\Http\Middleware\BodyParserMiddleware;
use Cake\Http\Middleware\CsrfProtectionMiddleware;
use Cake\Http\MiddlewareQueue;
use Cake\ORM\Locator\TableLocator;
use Cake\Routing\Middleware\AssetMiddleware;
use Cake\Routing\Middleware\RoutingMiddleware;

// Authentication
use Authentication\AuthenticationService;
use Authentication\AuthenticationServiceInterface;
use Authentication\AuthenticationServiceProviderInterface;
use Authentication\Middleware\AuthenticationMiddleware;
use Cake\Routing\Router;
use Psr\Http\Message\ServerRequestInterface;

// Authorization
use Authorization\AuthorizationService;
use Authorization\AuthorizationServiceInterface;
use Authorization\AuthorizationServiceProviderInterface;
use Authorization\Middleware\AuthorizationMiddleware;
use Authorization\Policy\OrmResolver;

//custom
use App\Middleware\TwofactorMiddleware;
use App\Middleware\AutologoutMiddleware;

class Application extends BaseApplication implements AuthenticationServiceProviderInterface,  AuthorizationServiceProviderInterface
{

    public function bootstrap(): void
    {
        // Call parent to load bootstrap from files.
        parent::bootstrap();

        if (PHP_SAPI === 'cli') {
            $this->bootstrapCli();
        } else {
            FactoryLocator::add(
                'Table',
                (new TableLocator())->allowFallbackClass(false)
            );
        }

        if (Configure::read('debug')) {
            $this->addPlugin('DebugKit');
        }

        $this->addPlugin('Authorization');
        $this->addPlugin('CakePdf');
        $this->addPlugin('TwoFactorAuth');
        // Load more plugins here
    }

    public function middleware(MiddlewareQueue $middlewareQueue): MiddlewareQueue
    {
        $csrf = new CsrfProtectionMiddleware([
            'httponly' => true
        ]);
        $csrf->skipCheckCallback(function (ServerRequestInterface $request) {
            $params = $request->getAttribute('params');
            return $params['controller'] !== 'Api';
        });
        $middlewareQueue

            ->add(new ErrorHandlerMiddleware(Configure::read('Error')))

            ->add(new AssetMiddleware([
                'cacheTime' => Configure::read('Asset.cacheTime'),
            ]))

            ->add(new RoutingMiddleware($this))
            ->add(new AuthenticationMiddleware($this))
            ->add(new BodyParserMiddleware())
            ->add($csrf)
            ->add(new TwofactorMiddleware)
            ->add(new AutologoutMiddleware);
            // ->add(new CsrfProtectionMiddleware([
            //     'httponly' => true,
            // ]));

        $middlewareQueue->add(new AuthorizationMiddleware($this));

        // $middlewareQueue->add(new \App\Middleware\AutologoutMiddleware());

        return $middlewareQueue;
    }

    public function getAuthenticationService(ServerRequestInterface $request): AuthenticationServiceInterface
    {
        $authenticationService = new AuthenticationService([
            'unauthenticatedRedirect' => Router::url('/'),
            'queryParam' => 'redirect',
        ]);

        // Load identifiers, ensure we check email and password fields
        $authenticationService->loadIdentifier('Authentication.Password', [
            'fields' => [
                'username' => 'email',
                'password' => 'password',
            ]
        ]);

        // Load the authenticators, you want session first
        $authenticationService->loadAuthenticator('Authentication.Session');

        // $authenticationService->loadAuthenticator('TwoFactorAuth.TwoFactorForm', [
        //     'fields' => [
        //         'username' => 'email',
        //         'password' => 'password',
        //     ],
        //     'loginUrl' => Router::url('/'),
        // ]);
        
        // // Configure form data check to pick email and password
        $authenticationService->loadAuthenticator('Authentication.Form', [
            'fields' => [
                'username' => 'email',
                'password' => 'password',
            ],
            'loginUrl' => Router::url('/'),
        ]);

        return $authenticationService;
    }

    public function getAuthorizationService(ServerRequestInterface $request): AuthorizationServiceInterface
    {
        $resolver = new OrmResolver();

        return new AuthorizationService($resolver);
    }

    public function services(ContainerInterface $container): void
    {
    }


    protected function bootstrapCli(): void
    {
        $this->addOptionalPlugin('Cake/Repl');
        $this->addOptionalPlugin('Bake');

        $this->addPlugin('Migrations');
       
        // Load more plugins here
    }
}