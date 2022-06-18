<?php

namespace App\Controller;

use App\Entity\Secret;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class SecretController{

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/v1/secret/{hash}", methods={"GET","HEAD"})
     */
    public function getSecret(string $hash){

        /**
         * @var \App\Entity\Secret $secret
         */
        $secret = $this->entityManager->getRepository(\App\Entity\Secret::class)->findOneBy([
            "hash" => $hash
        ]);
        if($this->checkRemViews($secret) && $this->checkExpiresTime($secret)){
            return new JsonResponse([
                "hash"=> $secret->getHash(),
                "secretText"=> $secret->getSecretText(),
                "createdAt"=> $secret->getCreatedAt(),
                "expiresAt"=> $secret->getExpiresAt(),
                "remainingViews"=> $secret->getRemainingViews()
            ]);
        }else{
            return new JsonResponse([
                "error"=> "not found"
            ]);
        }
    }

    /**
     * @Route("/v1/secret"), methods={"POST"}
     */
    public function postSecret(Request $request){

        /**
         * @var \App\Entity\Secret $secret
         */
        if (0 === strpos($request->headers->get('Content-Type'), 'application/json')) {
            $request = json_decode($request->getContent(), true);
        }
        if (0 === strpos($request->headers->get('Content-Type'), 'application/xml')) {
            $request = $this->xmlToArray($request->getContent());
        }
        if(isset($request["secret"]) && isset($request["expireAfter"]) && isset($request["expireAfterViews"])){

            $newsecret = new \App\Entity\Secret();
            $newsecret->setHash($this->createHash());
            $newsecret->setSecretText($request["secret"]);
            $newsecret->setExpiresAt($request["expireAfter"]);
            $newsecret->setCreatedAt(date('Y-m-d H:i:s'));
            $newsecret->setRemainingViews($request["expireAfterViews"]);
            $this->entityManager->persist($newsecret);
            $this->entityManager->flush();
    
            return new JsonResponse([
                "hash"=> $newsecret->getHash(),
                "secretText"=> $newsecret->getSecretText(),
                "createdAt"=> $newsecret->getCreatedAt(),
                "expiresAt"=> $newsecret->getExpiresAt(),
                "remainingViews"=> $newsecret->getRemainingViews()
            ]);
        }else{
            return new JsonResponse([
                "error"=> "no data"
            ]);
        }
    }


    private function checkExpiresTime($secret){
        $curDate = date('Y-m-d H:i:s');
        if($secret->getExpiresAt()<$curDate){
            return false;
        }else{
            return true;
        }
    }

    private function checkRemViews($secret){
        if($secret->getRemainingViews()>0){
            $secret->SetRemainingViews($secret->getRemainingViews()-1);
            $this->entityManager->flush();
            return true;
        }else{
            return false;
        }
       
    }
    private function createHash(){
        return md5(rand(100000, 999999999999999));
    }

    private function xmlToArray($xmlstring){
    
        $xml = simplexml_load_string($xmlstring, "SimpleXMLElement", LIBXML_NOCDATA);
        $json = json_encode($xml);
        $array = json_decode($json,TRUE);
      
        return $array;
      
      }
}