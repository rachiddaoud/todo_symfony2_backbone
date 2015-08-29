<?php

use Symfony\Component\Routing\Exception\MethodNotAllowedException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\RequestContext;

/**
 * appProdUrlMatcher
 *
 * This class has been auto-generated
 * by the Symfony Routing Component.
 */
class appProdUrlMatcher extends Symfony\Bundle\FrameworkBundle\Routing\RedirectableUrlMatcher
{
    /**
     * Constructor.
     */
    public function __construct(RequestContext $context)
    {
        $this->context = $context;
    }

    public function match($pathinfo)
    {
        $allow = array();
        $pathinfo = rawurldecode($pathinfo);

        // toptal_todo_default_index
        if (rtrim($pathinfo, '/') === '') {
            if (substr($pathinfo, -1) !== '/') {
                return $this->redirect($pathinfo.'/', 'toptal_todo_default_index');
            }

            return array (  '_controller' => 'Toptal\\TodoBundle\\Controller\\DefaultController::indexAction',  '_route' => 'toptal_todo_default_index',);
        }

        if (0 === strpos($pathinfo, '/todo')) {
            // toptal_todo_todo_get
            if (rtrim($pathinfo, '/') === '/todo') {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_toptal_todo_todo_get;
                }

                if (substr($pathinfo, -1) !== '/') {
                    return $this->redirect($pathinfo.'/', 'toptal_todo_todo_get');
                }

                return array (  '_controller' => 'Toptal\\TodoBundle\\Controller\\TodoController::getAction',  '_route' => 'toptal_todo_todo_get',);
            }
            not_toptal_todo_todo_get:

            // toptal_todo_todo_getsingle
            if (preg_match('#^/todo/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                if (!in_array($this->context->getMethod(), array('GET', 'HEAD'))) {
                    $allow = array_merge($allow, array('GET', 'HEAD'));
                    goto not_toptal_todo_todo_getsingle;
                }

                return $this->mergeDefaults(array_replace($matches, array('_route' => 'toptal_todo_todo_getsingle')), array (  '_controller' => 'Toptal\\TodoBundle\\Controller\\TodoController::getSingleAction',));
            }
            not_toptal_todo_todo_getsingle:

            // toptal_todo_todo_post
            if ($pathinfo === '/todo/') {
                if ($this->context->getMethod() != 'POST') {
                    $allow[] = 'POST';
                    goto not_toptal_todo_todo_post;
                }

                return array (  '_controller' => 'Toptal\\TodoBundle\\Controller\\TodoController::postAction',  '_route' => 'toptal_todo_todo_post',);
            }
            not_toptal_todo_todo_post:

            // toptal_todo_todo_put
            if (preg_match('#^/todo/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                if ($this->context->getMethod() != 'PUT') {
                    $allow[] = 'PUT';
                    goto not_toptal_todo_todo_put;
                }

                return $this->mergeDefaults(array_replace($matches, array('_route' => 'toptal_todo_todo_put')), array (  '_controller' => 'Toptal\\TodoBundle\\Controller\\TodoController::putAction',));
            }
            not_toptal_todo_todo_put:

            // toptal_todo_todo_delete
            if (preg_match('#^/todo/(?P<id>[^/]++)$#s', $pathinfo, $matches)) {
                if ($this->context->getMethod() != 'DELETE') {
                    $allow[] = 'DELETE';
                    goto not_toptal_todo_todo_delete;
                }

                return $this->mergeDefaults(array_replace($matches, array('_route' => 'toptal_todo_todo_delete')), array (  '_controller' => 'Toptal\\TodoBundle\\Controller\\TodoController::deleteAction',));
            }
            not_toptal_todo_todo_delete:

        }

        if (0 === strpos($pathinfo, '/user')) {
            // toptal_todo_user_login
            if ($pathinfo === '/user/login') {
                if ($this->context->getMethod() != 'POST') {
                    $allow[] = 'POST';
                    goto not_toptal_todo_user_login;
                }

                return array (  '_controller' => 'Toptal\\TodoBundle\\Controller\\UserController::loginAction',  '_route' => 'toptal_todo_user_login',);
            }
            not_toptal_todo_user_login:

            // toptal_todo_user_register
            if ($pathinfo === '/user/register') {
                if ($this->context->getMethod() != 'POST') {
                    $allow[] = 'POST';
                    goto not_toptal_todo_user_register;
                }

                return array (  '_controller' => 'Toptal\\TodoBundle\\Controller\\UserController::registerAction',  '_route' => 'toptal_todo_user_register',);
            }
            not_toptal_todo_user_register:

        }

        throw 0 < count($allow) ? new MethodNotAllowedException(array_unique($allow)) : new ResourceNotFoundException();
    }
}
