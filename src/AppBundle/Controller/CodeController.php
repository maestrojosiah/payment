<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\Code;
use Symfony\Component\HttpFoundation\RedirectResponse;

class CodeController extends Controller
{

    /**
     * @Route("/code/generate/{qty}", name="generate_codes")
     */ 
    public function feedAction(Request $request, $qty){
        $data = [];

        // uncomment lines below to add codes to database
        // for ($i=0; $i < $qty; $i++) { 
        //     $randomString = $this->getRandStr();
        //     $code = new Code();
        //     $code->setRandomCode($randomString);
        //     $code->setStatus('virgin');
        //     $data[] = $randomString;
        //     $this->save($code);
        // }

        // display 5 random codes if exists in database.
        $random_codes = $this->em()->getRepository('AppBundle:Code')
            ->getRandomCodes(5);
        $codes = [];
        foreach($random_codes as $code){ $codes[] = $code->getRandomCode(); }

        $string_of_codes = implode(", ", $codes);

        $data['rand_str'] = $string_of_codes;
        return $this->render('code/code.html.twig', $data);     
    }

	public function getRandStr(){
	  	$a = $b = '';

	  	for($i = 0; $i < 3; $i++){
		    $a .= chr(mt_rand(65, 90)); // see the ascii table why 65 to 90.    
		    $b .= mt_rand(0, 9);
		}

  		return $a .'-'. $b;
	}

    private function em(){
        $em = $this->getDoctrine()->getManager();
        return $em;
    }

    private function save($entity){
        $this->em()->persist($entity);
        $this->em()->flush();        
    } 

}
