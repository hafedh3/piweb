<?php

namespace ProduitBundle\Controller;

use ProduitBundle\Entity\FosUser;
use ProduitBundle\Entity\likes;
use ProduitBundle\Entity\Produit;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Produit controller.
 *
 */
class ProduitController extends Controller
{
    /**
     * Lists all produit entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();


        $produit = $em->getRepository('ProduitBundle:Produit')->findByIdUser($this->getUser());

        return $this->render('produit/index.html.twig', array(
            'produits' => $produit,
        ));
    }

    /**
     * Creates a new produit entity.
     *
     */
    public function newAction(Request $request)
    {
        $produit = new Produit();
        $form = $this->createForm('ProduitBundle\Form\ProduitType', $produit);
        $form->handleRequest($request);
        $produit->setIdUser($this->getUser());
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($produit);
            $em->flush();

            return $this->redirectToRoute('produit_show', array('idProduit' => $produit->getIdproduit()));
        }

        return $this->render('produit/new.html.twig', array(
            'produit' => $produit,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a produit entity.
     *
     */
    public function showAction(Produit $produit)
    {
        $deleteForm = $this->createDeleteForm($produit);
        return $this->render('produit/show.html.twig', array(
            'produit' => $produit,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing produit entity.
     *
     */
    public function editAction(Request $request, Produit $produit)
    {
        $deleteForm = $this->createDeleteForm($produit);
        $editForm = $this->createForm('ProduitBundle\Form\ProduitType', $produit);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('produit_edit', array('idProduit' => $produit->getIdproduit()));
        }

        return $this->render('produit/edit.html.twig', array(
            'produit' => $produit,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a produit entity.
     *
     */
    public function deleteAction(Request $request, Produit $produit)
    {
        $form = $this->createDeleteForm($produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($produit);
            $em->flush();
        }

        return $this->redirectToRoute('produit_index');
    }

    /**
     * Creates a form to delete a produit entity.
     *
     * @param Produit $produit The produit entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Produit $produit)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('produit_delete', array('idProduit' => $produit->getIdproduit())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

    public function likeAction($idProduit)
    {
        $like=new likes();
        $produit = $this->getDoctrine()->getRepository(Produit::class)->find($idProduit);
        $user = $this->getDoctrine()->getRepository(FosUser::class)->find($this->getUser());

        $like->setIdproduit($produit);
        $like->setIduser($user);

        $this->getDoctrine()->getManager()->persist($like);
        $this->getDoctrine()->getManager()->flush();


        return $this->redirect($_SERVER['HTTP_REFERER']);
    }

    public function dislikeAction($idProduit)
    {
        $like = $this->getDoctrine()->getRepository(likes::class)->findByIdproduit($idProduit);
        var_dump($like).die();
        $this->getDoctrine()->getManager()->remove($like);
        $this->getDoctrine()->getManager()->flush();
        return $this->redirect($_SERVER['HTTP_REFERER']);
    }

    public function nombrevueAction($idProduit,$nbrvue)
    { $produit = $this->getDoctrine()->getRepository(Produit::class)->find($idProduit);
    $produit->setNbrvue($nbrvue+1);
        $this->getDoctrine()->getManager()->persist($produit);
        $this->getDoctrine()->getManager()->flush();




        return $this->redirect($_SERVER['HTTP_REFERER']);
    }



}
