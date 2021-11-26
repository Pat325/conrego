<?php

namespace App\Controller;

use App\Entity\Vote;
use App\Form\VoteType;
use App\Repository\VoteRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use Dompdf\Dompdf;
use Dompdf\Options;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="main")
     */
    public function index(Request $request, VoteRepository $voteRepository): Response
    {

        $vote = new Vote();        
        $form = $this->createForm(VoteType::class, $vote);

        return $this->render('main/index.html.twig', [
            'controller_name' => 'MainController',
            'form' => $form->createView()          
        ]);
    }

    /**
     * @Route("/results", name="results")
     */
    public function results(Request $request, VoteRepository $voteRepository): Response
    {
        
        $tab = array();
        $results = $voteRepository->results();        
        if(!empty($results)) {            
            if(isset($results[0])) {
                foreach($results[0] as $k=>$v) {
                    $tab[] = array("label" => $k, "value" => $v);
                }
            }
        }
        
        $response = new Response();
        $response->setContent(json_encode([            
            'results' => $tab
        ]));

        return $response;
    }

    /**
     * @Route("/submit", name="submit", methods={"POST"})     
     */
    public function submitForm(Request $request): Response
    {

        $response = new Response();

        $vote = new Vote();
        $date = date("Y-m-d");
        $vote->setAdded(\DateTime::createFromFormat('Y-m-d', $date));
        $form = $this->createForm(VoteType::class, $vote);
        $form->handleRequest($request);        
        
                
            if ($form->isSubmitted() && $form->isValid()){

                $email = $form->get('email')->getData();                
                
                $results = $this->getDoctrine()->getRepository(Vote::class)->findBy(
                    array('email' => $email),                     
                );

                if(count($results) > 0) {

                    $response->setContent(json_encode([
                        'error' => 1,
                        'message' => 'Nie możesz głosować 2x',
                    ]));

                } else {

                    $em = $this->getDoctrine()->getManager();                    
                    $em->persist($vote);
                    $em->flush();

                    $response->setContent(json_encode([
                        'error' => 0,
                        'message' => 'Ok. Ocena została dodana.',
                    ]));

                }
                
            }
            else {
                $response->setContent(json_encode([
                    'error' => 1,
                    'message' => 'Formularz zawiera błędy. Ocena nie została dodana.',
                ]));

            }
    
       

        return $response;
    
    }

    /**
    * @Route("/pdf", name="pdf")
    */
    public function pdf(VoteRepository $voteRepository)
    {

        $response = new Response();

        $results = $voteRepository->resultsByDay();             
        $this->generatePDF($results);

        if(file_exists('pdf.pdf')) {

            $filename = 'pdf.pdf';
            $response->headers->set('Cache-Control', 'private');
            $response->headers->set('Content-type', 'application/pdf');
            $response->headers->set('Content-Disposition', 'attachment; filename="' . basename($filename) . '"');
            $response->sendHeaders();
            $response->setContent(readfile($filename));

        } else {

            $response->setContent(json_encode([
                'error' => 1,
                'message' => 'Sorry, no file.',
            ]));

        }
       
         return $response;

    }

    public function generatePDF($results)
    {
        
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');
        $dompdf = new Dompdf($pdfOptions);
                
        $html = $this->renderView('pdf.html.twig', [
            'results' => $results
        ]);
        
        $dompdf->loadHtml($html);        
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream("pdf.pdf", [
            "Attachment" => true
        ]);
        
    }

}
