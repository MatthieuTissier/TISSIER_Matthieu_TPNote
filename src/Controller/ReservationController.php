<?php
// src/Controller/ReservationController.php
namespace App\Controller;

use App\Entity\Reservation;
use App\Form\ReservationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ReservationController extends AbstractController
{
    /**
     * @Route("/reservation/new", name="reservation_new")
     */
    public function new(Request $request, EntityManagerInterface $em, ValidatorInterface $validator): Response
    {
        // Créer une nouvelle réservation
        $reservation = new Reservation();
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Validation des contraintes de réservation
            $errors = $validator->validate($reservation);
            if (count($errors) > 0) {
                foreach ($errors as $error) {
                    $this->addFlash('error', $error->getMessage());
                }
                return $this->redirectToRoute('reservation_new');
            }

            // Assigner l'utilisateur connecté à la réservation
            $reservation->setUser($this->getUser());

            // Persister la réservation
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

    /**
     * @Route("/admin/reservations", name="admin_reservations")
     */
    public function index(EntityManagerInterface $em): Response
    {
        // Vérifier que l'utilisateur a le rôle admin
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        // Récupérer toutes les réservations
        $reservations = $em->getRepository(Reservation::class)->findAll();

        return $this->render('reservation/admin_index.html.twig', [
            'reservations' => $reservations,
        ]);
    }

    /**
     * @Route("/admin/reservation/{id}/edit", name="reservation_edit")
     */
    public function edit(Reservation $reservation, Request $request, EntityManagerInterface $em, ValidatorInterface $validator): Response
    {
        // Vérifier que l'utilisateur a le rôle admin
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Validation des contraintes de réservation
            $errors = $validator->validate($reservation);
            if (count($errors) > 0) {
                foreach ($errors as $error) {
                    $this->addFlash('error', $error->getMessage());
                }
                return $this->redirectToRoute('reservation_edit', ['id' => $reservation->getId()]);
            }

            // Mettre à jour la réservation
            $em->flush();

            $this->addFlash('success', 'Réservation mise à jour !');
            return $this->redirectToRoute('admin_reservations');
        }

        return $this->render('reservation/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/reservation/{id}/delete", name="reservation_delete")
     */
    public function delete(Reservation $reservation, EntityManagerInterface $em): Response
    {
        // Vérifier que l'utilisateur a le rôle admin
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        // Supprimer la réservation
        $em->remove($reservation);
        $em->flush();

        $this->addFlash('success', 'Réservation supprimée avec succès!');
        return $this->redirectToRoute('admin_reservations');
    }

    /**
     * @Route("/user/reservations", name="user_reservations")
     */
    public function userReservations(EntityManagerInterface $em): Response
    {
        // Vérifier que l'utilisateur est connecté
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        // Récupérer les réservations de l'utilisateur connecté
        $reservations = $em->getRepository(Reservation::class)->findBy(['user' => $user]);

        return $this->render('reservation/user_index.html.twig', [
            'reservations' => $reservations,
        ]);
    }
}
