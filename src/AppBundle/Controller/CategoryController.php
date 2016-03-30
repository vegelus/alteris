<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Entity\Category;
use AppBundle\Form\CategoryType;

/**
 * Category controller.
 *
 * @Route("/category")
 */
class CategoryController extends Controller {

    /**
     * Lists all Category entities.
     *
     * @Route("/", name="category")
     * @Method("GET")
     */
    public function indexAction() {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('AppBundle:Category')->findBy(array(), array('root' => 'ASC', 'lft' => 'ASC'));
        
        return $this->render('category/index.html.twig', array(
            'entities' => $entities,
        ));
    }

    /**
     * Creates a new Category entity.
     *
     * @Route("/create", name="category_create")
     * @Method("POST")
     */
    public function createAction(Request $request ) {
        $entity = new Category();
        $em = $this->getDoctrine()->getManager();
        
        //$parent = $em->getRepository('AppBundle:Category')->find($request->request->get('appbundle_categorytype')['choices']);
        
        //$entity->setParent($parent);
        
        $form = $this->createForm(new CategoryType(), $entity);

        $form->bind($request);

        
        
        if ($form->isValid()) {
            
            $em->persist($entity);
            $em->flush();
            $repo = $em->getRepository('AppBundle:Category');
            $repo->verify();
            $repo->recover();
            $repo->clear();
            return $this->redirect($this->generateUrl('category'));
        }

        return $this->render('category/new.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
        ));
    }

    /**
     * Displays a form to create a new Category entity.
     *
     * @Route("/new", name="category_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction() {
         $em = $this->getDoctrine()->getManager();
        $entity = new Category($em);
        $form = $this->createForm(new CategoryType(), $entity);

        return $this->render('category/new.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Category entity.
     *
     * @Route("/edit/{id}", name="category_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:Category')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Category entity.');
        }

        $editForm = $this->createForm(new CategoryType($em), $entity);

        
        return $this->render('category/edit.html.twig', array(
            'entity' => $entity,
            'form' => $editForm->createView(),
        ));
    }

    /**
     * Edits an existing Category entity.
     *
     * @Route("/update/{id}", name="category_update")
     * @Method("PUT|POST")
     * @Template("AppBundle:Category:edit.html.twig")
     */
    public function updateAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('AppBundle:Category')->find($id);
        
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Category entity.');
        }

        $editForm = $this->createForm(new CategoryType($em), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();
            $repo = $em->getRepository('AppBundle:Category');
            $repo->verify();
            $repo->recover();
            $repo->clear();

            return $this->redirect($this->generateUrl('category'));
        }

        return $this->render('category/edit.html.twig', array(
            'entity' => $entity,
            'form' => $editForm->createView(),
        ));
    }

    /**
     * Deletes a Category entity.
     *
     * @Route("/delete/{id}", name="category_delete")
     * @Method("DELETE|GET")
     */
    public function deleteAction(Request $request, $id) {

        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('AppBundle:Category')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Category entity.');
        }
        $repo = $em->getRepository('AppBundle:Category');
        $vegies = $repo->findOneByTitle($entity->getTitle());
        $repo->removeFromTree($vegies);
        $repo->recover(); // clear cached nodes
        //$em->remove($entity);
        //$em->flush();

        return $this->redirect($this->generateUrl('category'));
    }

    /**
     * Move up a Category entity.
     *
     * @Route("/moveup/{id}", name="category_moveup")
     * @Method("DELETE|GET")
     */
    public function moveUpAction($id) {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('AppBundle:Category');
        $entities = $em->getRepository('AppBundle:Category')->find($id);
        $node = $repo->findOneByTitle($entities->getTitle());
        $repo->moveUp($node, 1);
        $repo->verify();
        $repo->recover();
        $repo->clear(); // clear cached nodes
        return $this->redirect($this->generateUrl('category'));
    }

    /**
     * Move down a Category entity.
     *
     * @Route("/movedown/{id}", name="category_movedown")
     * @Method("DELETE|GET")
     */
    public function moveDownAction($id) {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository('AppBundle:Category');
        $entities = $em->getRepository('AppBundle:Category')->find($id);
        $node = $repo->findOneByTitle($entities->getTitle());
        $repo->moveDown($node, 1);
        $repo->verify();
        $repo->recover();
        $repo->clear(); // clear cached nodes
        return $this->redirect($this->generateUrl('category'));
    }

}
