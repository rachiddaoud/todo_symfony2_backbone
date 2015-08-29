<?php

namespace Toptal\TodoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

use Toptal\TodoBundle\Entity\User;
use Toptal\TodoBundle\Entity\Todo;
use Toptal\TodoBundle\Form\TodoType;

/**
 * @Route("/todo")
 */
class TodoController extends Controller
{
    /**
    * Check the token and the user validity
    */
    public function getUser(){
        if($token = $this->getRequest()->query->get('token')){
            $em = $this->getDoctrine()->getManager();

            $token = $em->getRepository('TodoBundle:Token')->findOneBy(array('token'=>$token));
            //check if the token exist and is still valid
            if(!$token || ($token->getExpiryDate()->getTimestamp() < time())){
                return false;
            }

            $date = new \DateTime();
            $token->setExpiryDate($date->setTimestamp(time()+300));

            $em->persist($token);
            $em->flush();

            return $token->getUser();
        }else{
            return false;
        }
    }

    /**
     * @Route("/")
     * @Method({"GET"})
     */
    public function getAction()
    {
        if($user = $this->getUser()){
            $em = $this->getDoctrine()->getManager();
            //Get all the not completed todos
            $todos = $em->getRepository('TodoBundle:Todo')->findBy(array('user'=>$user,'completed'=>false),array('duedate'=>'desc'));

            $todos_temp = array();
            foreach ($todos as $todo) {
                $todo_temp['id'] = $todo->getId();
                $todo_temp['title'] = $todo->getTitle();
                $todo_temp['duedate'] = $todo->getDueDate()->format('m-d-Y');
                $todo_temp['priority'] = $todo->getPriority();
                $todo_temp['completed'] = $todo->getCompleted();

                $todos_temp[] = $todo_temp;
            }

            return $this->jsonResponse($todos_temp,200);
        }else{
            return $this->jsonResponse(array(
                'code' => '1001',
                'message' => 'You are not authorized, please log in and try again.'
            ),401);
        }
    }

    /**
     * @Route("/{id}")
     * @Method({"GET"})
     */
    public function getSingleAction($id)
    {
        if($user = $this->getUser()){

            $em = $this->getDoctrine()->getManager();
            $todo = $em->getRepository('TodoBundle:Todo')->findOneBy(array('user'=>$user,'id'=>$id));

            if (!$todo) {
                return $this->jsonResponse(array(
                    'code' => '2001',
                    'message' => 'The Todo you requested does not exist.'
                ),404);
            }

            $todo_temp = array();
            $todo_temp['id'] = $todo->getId();
            $todo_temp['title'] = $todo->getTitle();
            $todo_temp['duedate'] = $todo->getDueDate()->format('m-d-Y');
            $todo_temp['priority'] = $todo->getPriority();
            $todo_temp['completed'] = $todo->getCompleted();

            return $this->jsonResponse($todo_temp,200);
        }else{
            return $this->jsonResponse(array(
                'code' => '1001',
                'message' => 'You are not authorized, please log in and try again.'
            ),401);
        }
    }

    /**
     * @Route("/")
     * @Method({"POST"})
     */
    public function postAction()
    {
        if($user = $this->getUser()){

            $em = $this->getDoctrine()->getManager();
            $todo = new Todo();
            $form = $this->createForm(new TodoType(), $todo);
            $jsonData = json_decode($this->getRequest()->getContent(), true);
            
            $form->bind($jsonData);

            if ($form->isValid()) {
                $todo->setUser($user);

                $todo->setTitle(htmlentities($todo->getTitle()));
                $em->persist($todo);
                $em->flush();

                $todo_temp = array();
                $todo_temp['id'] = $todo->getId();
                $todo_temp['title'] = $todo->getTitle();
                $todo_temp['duedate'] = $todo->getDueDate()->format('m-d-Y');
                $todo_temp['priority'] = $todo->getPriority();
                $todo_temp['completed'] = $todo->getCompleted();
                return $this->jsonResponse($todo_temp,201);
            }else{
                $messages = array();
                foreach($form as $field){
                    if($field->getErrors()){
                        //$errors['']
                        foreach ($field->getErrors() as $error) {
                            $messages[$field->getName()] = $error->getMessage();
                        }
                    }
                }
                
                return $this->jsonResponse(array(
                    'code' => '2000',
                    'message'=>'Invalid todo form.',
                    'errors' => $messages
                ),400);
            }
        }else{
            return $this->jsonResponse(array(
                'code' => '1001',
                'message' => 'You are not authorized, please log in and try again.'
            ),401);
        }
    }

    /**
     * @Route("/{id}")
     * @Method({"PUT"})
     */
    public function putAction($id)
    {
        if($user = $this->getUser()){
            $em = $this->getDoctrine()->getManager();
            $todo = $em->getRepository('TodoBundle:Todo')->findOneBy(array('id'=>$id,'user'=>$user));

            if (!$todo) {
                return $this->jsonResponse(array(
                    'code' => '2001',
                    'message' => 'The Todo you requested does not exist.'
                ),404);
            }

            $form = $this->createForm(new TodoType(), $todo);
            $jsonData = json_decode($this->getRequest()->getContent(), true);
            
            $form->bind($jsonData);

            if ($form->isValid()) {
                $todo->setTitle(strip_tags($todo->getTitle()));
                $em->persist($todo);
                $em->flush();

                $todo_temp = array();
                $todo_temp['id'] = $todo->getId();
                $todo_temp['title'] = $todo->getTitle();
                $todo_temp['duedate'] = $todo->getDueDate()->format('m-d-Y');
                $todo_temp['priority'] = $todo->getPriority();
                $todo_temp['completed'] = $todo->getCompleted();
                return $this->jsonResponse($todo_temp,201);
            }else{

                $messages = array();
                foreach($form as $field){
                    if($field->getErrors()){
                        //$errors['']
                        foreach ($field->getErrors() as $error) {
                            $messages[$field->getName()] = $error->getMessage();
                        }
                    }
                }
                
                return $this->jsonResponse(array(
                    'code' => '2000',
                    'message'=>'Invalid todo form.',
                    'errors' => $messages
                ),400);
            }
        }else{
            return $this->jsonResponse(array(
                'code' => '1001',
                'message' => 'You are not authorized, please log in and try again.'
            ),401);
        }
    }

    /**
     * @Route("/{id}")
     * @Template()
     * @Method({"DELETE"})
     */
    public function deleteAction($id)
    {
        if($user = $this->getUser()){
            $em = $this->getDoctrine()->getManager();
            $todo = $em->getRepository('TodoBundle:Todo')->findOneBy(array('id'=>$id,'user'=>$user));

            if (!$todo) {
                return $this->jsonResponse(array(
                    'code' => '2001',
                    'message' => 'The Todo you requested does not exist.'
                ),404);
            }
            $em->remove($todo);
            $em->flush();

            $response = new Response();
            $response->setStatusCode(204);
            return $response;
        }else{
            return $this->jsonResponse(array(
                'code' => '1001',
                'message' => 'You are not authorized, please log in and try again.'
            ),401);
        }
    }

    public function jsonResponse($array,$code = 200){
        $response = new JsonResponse($array);
        $response->setStatusCode($code);
        return $response;
    }
}
