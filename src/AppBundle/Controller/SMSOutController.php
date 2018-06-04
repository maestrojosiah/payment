<?php

namespace AppBundle\Controller;

use AppBundle\Entity\SMSOut;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;
use AppBundle\Service\SendMessage;

/**
 * Smsout controller.
 *
 * @Route("smsout")
 */
class SMSOutController extends Controller
{
    /**
     * Lists all sMSOut entities.
     *
     * @Route("/", name="smsout_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $sMSOuts = $em->getRepository('AppBundle:SMSOut')->findAll();

        return $this->render('smsout/index.html.twig', array(
            'sMSOuts' => $sMSOuts,
        ));
    }

    /**
     * Creates a new sMSOut entity.
     *
     * @Route("/new", name="smsout_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request, SendMessage $sendMessage)
    {
        $sMSOut = new Smsout();
        $form = $this->createForm('AppBundle\Form\SMSOutType', $sMSOut);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $form_data = $form->getData();
            $phone_no = $form_data->getSendTo();
            $message = $form_data->getMessage();

            $send = $sendMessage->sendMessage($phone_no, $message);
            $this->addFlash('success', "Message sent");
            $em = $this->getDoctrine()->getManager();
            $em->persist($sMSOut);
            $em->flush();

            return $this->redirectToRoute('smsout_show', array('id' => $sMSOut->getId()));
        }

        return $this->render('smsout/new.html.twig', array(
            'sMSOut' => $sMSOut,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a sMSOut entity.
     *
     * @Route("/{id}", name="smsout_show")
     * @Method("GET")
     */
    public function showAction(SMSOut $sMSOut)
    {
        $deleteForm = $this->createDeleteForm($sMSOut);

        return $this->render('smsout/show.html.twig', array(
            'sMSOut' => $sMSOut,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing sMSOut entity.
     *
     * @Route("/{id}/edit", name="smsout_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, SMSOut $sMSOut)
    {
        $deleteForm = $this->createDeleteForm($sMSOut);
        $editForm = $this->createForm('AppBundle\Form\SMSOutType', $sMSOut);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('smsout_edit', array('id' => $sMSOut->getId()));
        }

        return $this->render('smsout/edit.html.twig', array(
            'sMSOut' => $sMSOut,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a sMSOut entity.
     *
     * @Route("/{id}", name="smsout_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, SMSOut $sMSOut)
    {
        $form = $this->createDeleteForm($sMSOut);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($sMSOut);
            $em->flush();
        }

        return $this->redirectToRoute('smsout_index');
    }

    /**
     * Creates a form to delete a sMSOut entity.
     *
     * @param SMSOut $sMSOut The sMSOut entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(SMSOut $sMSOut)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('smsout_delete', array('id' => $sMSOut->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
