<?php
declare(strict_types=1);

namespace App\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Cake\ORM\TableRegistry;
use Cake\Routing\Router;
use Cake\Http\Response;
use Cake\Http\Request;

/**
 * AutologoutMiddleware middleware
 */
class AutologoutMiddleware implements MiddlewareInterface
{
    /**
     * Process method.
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request The request.
     * @param \Psr\Http\Server\RequestHandlerInterface $handler The request handler.
     * @return \Psr\Http\Message\ResponseInterface A response.
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {

        $currentUrl = $request->getRequestTarget();
        if($currentUrl == '/logout' || $currentUrl == '/' || $currentUrl == '/users/setup-two-factor' || $currentUrl == '/users/verify-two-factor' || $currentUrl == '/users/after-login') return $handler->handle($request);

        if($request->getAttribute('identity')) {

            // Access the session from the request object
            $session = $request->getSession();

            $session_user = $session->read('user');
            // Get a specific session value, e.g., 'user_id'
            $session_val = $session->read('session_val');
            // echo $session_val; exit;
            // Check if the session value exists
            if(!$session_user)
            {
                $url = Router::url(['controller' => 'Users','action' => 'logout']);
                // echo'<pre>'; print_r($url); exit;
                $response = new Response();
                $response = $response->withStatus(302)->withHeader('Location',$url);
                return $response;

                return $handler->handle($request);
            }
            else if ($session_val) {
                // Load the Table class
                $UsersTable = TableRegistry::getTableLocator()->get('Users');

                // Fetch data from the database (for example, find session value from user table)
                $users = $UsersTable->find()->select(['session_login'])->where(['id' => $request->getAttribute('identity')->get('id')])->first();
                
                if($users->session_login == $session_val)
                    return $handler->handle($request);
                else
                {
                    $url = Router::url(['controller' => 'Users','action' => 'logout']);
                    // echo'<pre>'; print_r($url); exit;
                    $response = new Response();
                    $response = $response->withStatus(302)->withHeader('Location',$url);
                    return $response;
                }
                    return $handler->handle($request);
            }

        }
        

        return $handler->handle($request);
    }
}
