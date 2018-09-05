<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Property;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Property controller.
 *
 * @Route("/")
 */
class PropertyController extends Controller
{

    /**
     * Lists all property entities.
     *
     * @Route("/", name="_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $properties = $em->getRepository('AppBundle:Property')->findAll();

        return $this->render('property/index.html.twig', array(
            'properties' => $properties,
        ));
    }

    /**
     * Creates a new property entity.
     *
     * @Route("/new", name="_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $property = new Property();
        $form = $this->createForm('AppBundle\Form\PropertyType', $property);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($property);
            $em->flush();

            return $this->redirectToRoute('_show', array('id' => $property->getId()));
        }

        return $this->render('property/new.html.twig', array(
            'property' => $property,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a property entity.
     *
     * @Route("/{id}", name="_show")
     * @Method("GET")
     */
    public function showAction(Property $property)
    {
        $deleteForm = $this->createDeleteForm($property);

        return $this->render('property/show.html.twig', array(
            'property' => $property,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing property entity.
     *
     * @Route("/{id}/edit", name="_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Property $property)
    {
        $deleteForm = $this->createDeleteForm($property);
        $editForm = $this->createForm('AppBundle\Form\PropertyType', $property);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('_edit', array('id' => $property->getId()));
        }

        return $this->render('property/edit.html.twig', array(
            'property' => $property,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a property entity.
     *
     * @Route("/{id}", name="_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Property $property)
    {
        $form = $this->createDeleteForm($property);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($property);
            $em->flush();
        }

        return $this->redirectToRoute('_index');
    }

    /**
     * Creates a form to delete a property entity.
     *
     * @param Property $property The property entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Property $property)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('_delete', array('id' => $property->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
