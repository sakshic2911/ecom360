<?php
declare(strict_types=1);

namespace App\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Cake\Routing\Router;
use Cake\Http\Response;
use Cake\Http\Request;
use App\View\Helper\ClientInfoHelper;

/**
 * Twofactor middleware
 */
class TwofactorMiddleware implements MiddlewareInterface
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
        
        if($currentUrl == '/users/setup-two-factor' || $currentUrl !== '/') return $handler->handle($request);

        if($request->getAttribute('identity') && $request->getAttribute('identity')->get('secret') !== null && $request->getAttribute('identity')->get('remember_me') == 0 && $request->getAttribute('identity')->get('delete_user') == 0) {
            if($request->getAttribute('identity')->get('device_id') !== null)
            {
                $device = base64_decode($request->getAttribute('identity')->get('device_id'));
                $device_info = $this->getOS().'_'.$this->getBrowser();
                if($device == $device_info && $request->getAttribute('identity')->get('remember_me') == 1)
                $url = Router::url(['controller' => 'Users','action' => 'afterLogin']);
                else
                $url = Router::url(['controller' => 'Users','action' => 'setupTwoFactor']);

            }
            else
            $url = Router::url(['controller' => 'Users','action' => 'setupTwoFactor']);

            $response = new Response();
            $response = $response->withStatus(302)->withHeader('Location',$url);
            return $response;
        }
        return $handler->handle($request);
    }

    private function getBrowser()
    {
        $browser="";
        if(strrpos(strtolower($_SERVER["HTTP_USER_AGENT"]),strtolower("MSIE")))
        {
        $browser="Internet Explorer";
        }
        else if(strrpos(strtolower($_SERVER["HTTP_USER_AGENT"]),strtolower("Presto")))
        {
        $browser="Opera";
        }
        else if(strrpos(strtolower($_SERVER["HTTP_USER_AGENT"]),strtolower("CHROME")))
        {
        $browser="Google Chrome";
        }
        else if(strrpos(strtolower($_SERVER["HTTP_USER_AGENT"]),strtolower("SAFARI")))
        {
        $browser="Safari";
        }
        else if(strrpos(strtolower($_SERVER["HTTP_USER_AGENT"]),strtolower("FIREFOX")))
        {
        $browser="FIREFOX";
        }
        else
        {
        $browser="OTHER";
        }

        return $browser;
    }

    private function getOS() { 

        $user_agent = $_SERVER['HTTP_USER_AGENT'];
    
        $os_platform  = "Unknown OS Platform";
    
        $os_array     = array(
                              '/windows nt 10/i'      =>  'Windows 10',
                              '/windows nt 6.3/i'     =>  'Windows 8.1',
                              '/windows nt 6.2/i'     =>  'Windows 8',
                              '/windows nt 6.1/i'     =>  'Windows 7',
                              '/windows nt 6.0/i'     =>  'Windows Vista',
                              '/windows nt 5.2/i'     =>  'Windows Server 2003/XP x64',
                              '/windows nt 5.1/i'     =>  'Windows XP',
                              '/windows xp/i'         =>  'Windows XP',
                              '/windows nt 5.0/i'     =>  'Windows 2000',
                              '/windows me/i'         =>  'Windows ME',
                              '/win98/i'              =>  'Windows 98',
                              '/win95/i'              =>  'Windows 95',
                              '/win16/i'              =>  'Windows 3.11',
                              '/macintosh|mac os x/i' =>  'Mac OS X',
                              '/mac_powerpc/i'        =>  'Mac OS 9',
                              '/linux/i'              =>  'Linux',
                              '/ubuntu/i'             =>  'Ubuntu',
                              '/iphone/i'             =>  'iPhone',
                              '/ipod/i'               =>  'iPod',
                              '/ipad/i'               =>  'iPad',
                              '/android/i'            =>  'Android',
                              '/blackberry/i'         =>  'BlackBerry',
                              '/webos/i'              =>  'Mobile'
                        );
    
        foreach ($os_array as $regex => $value)
            if (preg_match($regex, $user_agent))
                $os_platform = $value;
    
        return $os_platform;
    }
}
