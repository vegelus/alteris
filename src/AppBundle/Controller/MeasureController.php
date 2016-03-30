<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Measure;
use AppBundle\Form\MeasureType;

/**
 * Measure controller.
 *
 * @Route("/measure")
 */
class MeasureController extends Controller
{
    /**
     * Lists all Measure entities.
     *
     * @Route("/", name="measure_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $measures = $em->getRepository('AppBundle:Measure')->findAll();

        return $this->render('measure/index.html.twig', array(
            'measures' => $measures,
        ));
    }

    /**
     * Creates a new Measure entity.
     *
     * @Route("/new", name="measure_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $measure = new Measure();
        $form = $this->createForm('AppBundle\Form\MeasureType', $measure);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($measure);
            $em->flush();

            return $this->redirectToRoute('measure_show', array('id' => $measure->getId()));
        }

        return $this->render('measure/new.html.twig', array(
            'measure' => $measure,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Measure entity.
     *
     * @Route("/{id}", name="measure_show")
     * @Method("GET")
     */
    public function showAction(Measure $measure)
    {
        $deleteForm = $this->createDeleteForm($measure);

        return $this->render('measure/show.html.twig', array(
            'measure' => $measure,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Measure entity.
     *
     * @Route("/{id}/edit", name="measure_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Measure $measure)
    {
        $deleteForm = $this->createDeleteForm($measure);
        $editForm = $this->createForm('AppBundle\Form\MeasureType', $measure);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($measure);
            $em->flush();

            return $this->redirectToRoute('measure_edit', array('id' => $measure->getId()));
        }

        return $this->render('measure/edit.html.twig', array(
            'measure' => $measure,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Measure entity.
     *
     * @Route("/{id}", name="measure_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Measure $measure)
    {
        $form = $this->createDeleteForm($measure);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($measure);
            $em->flush();
        }

        return $this->redirectToRoute('measure_index');
    }

    /**
     * Creates a form to delete a Measure entity.
     *
     * @param Measure $measure The Measure entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Measure $measure)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('measure_delete', array('id' => $measure->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
