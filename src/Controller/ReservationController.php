<?php
// src/Controller/ReservationController.php
namespace App\Controller;

use App\Entity\Reservation;
use App\Form\ReservationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Routing\Annotation\Route;

class ReservationController extends AbstractController
{
    /**
     * @Route("/reservation/new", name="reservation_new")
     */
    public function new(Request $request, EntityManagerInterface $em, ValidatorInterface $validator)
    {
        $reservation = new Reservation();
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Validation manuelle des contraintes
            $errors = $validator->validate($reservation);
            if (count($errors) > 0) {
                foreach ($errors as $error) {
                    $this->addFlash('error', $error->getMessage());
                }
                return $this->redirectToRoute('reservation_new');
            }


            $em->persist($reservation);
            $em->flush();

            $this->addFlash('success', 'Réservation effectuée !');
            return $this->redirectToRoute('home'); 
        } else {
            $this->addFlash('error', 'La réservation a échoué, vérifiez vos informations svp.');
        }

        return $this->render('reservation/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}

