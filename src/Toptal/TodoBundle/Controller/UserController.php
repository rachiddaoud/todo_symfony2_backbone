<?php

namespace Toptal\TodoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder;

use Toptal\TodoBundle\Entity\Token;
use Toptal\TodoBundle\Entity\User;
use Toptal\TodoBundle\Form\UserType;

/**
 * @Route("/user")
 */
class UserController extends Controller
{
    /**
     * @Route("/login")
     * @Method({"POST"})
     */
    public function loginAction()
    {
            $em = $this->getDoctrine()->getManager();

            $user = new User();
            $form = $this->createForm(new UserType(), $user);
            $jsonData = json_decode($this->getRequest()->getContent(), true);
            $form->bind($jsonData);

            if ($form->isValid()) {
                //exit(var_dump($jsonData));
                $user_db = $em->getRepository('TodoBundle:User')->findOneBy(array('username'=>$user->getUsername()));

                $passencoder = new MessageDigestPasswordEncoder();
                $user->setPassword($passencoder->encodePassword($user->getPassword(),'salt'));

                if(!$user_db || $user_db->getPassword() != $user->getPassword()){
                    // false autorization
                    return $this->jsonResponse(array(
                        'code' => '2102',
                        'message'=>'Invalid username/password.'
                    ),400);
                }

                $token = new Token();
                $token->setToken(md5(uniqid().mt_rand()));
                $date = new \DateTime();
                $token->setExpiryDate($date->setTimestamp(time()+300));
                $token->setUser($user_db);

                $em->persist($token);
                $em->flush();

                return $this->jsonResponse(array('token'=>$token->getToken()));
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
                    'code' => '2100',
                    'message'=>'Invalid user form.',
                    'errors' => $messages
                ),400);
            }
    }

    /**
     * @Route("/register")
     * @Method({"POST"})
     */
    public function registerAction()
    {
            $em = $this->getDoctrine()->getManager();

            $user = new User();
            $form = $this->createForm(new UserType(), $user);
            $jsonData = json_decode($this->getRequest()->getContent(), true);
            $form->bind($jsonData);

            if ($form->isValid()) {
                if($em->getRepository('TodoBundle:User')->findOneBy(array('username'=>$user->getUsername()))){
                    return $this->jsonResponse(array(
                        'code' => '2101',
                        'message'=>'username already used.'
                    ),400);
                }
                $passencoder = new MessageDigestPasswordEncoder();
                $user->setPassword($passencoder->encodePassword($user->getPassword(),'salt'));
                $em->persist($user);
                $em->flush();

                $response = new Response();
                $response->setStatusCode(201);
                return $response;
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
                    'code' => '2100',
                    'message'=>'Invalid user form.',
                    'errors' => $messages
                ),400);
            }
    }

    public function jsonResponse($array,$code = 200){
        $response = new JsonResponse($array);
        $response->setStatusCode($code);
        return $response;
    }

}
