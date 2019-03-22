<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Todo;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;
// use Symfony\Component\Form\Forms;
use Symfony\Component\Form\Extension\HttpFoundation\HttpFoundationExtension;



class TodoController extends AbstractController
{
    /**
     * @Route("/todo", name="todo")
     */
    public function index()
    {
        // return $this->render('todo/index.html.twig', [
        //     'controller_name' => 'TodoController',
        // ]);
        // $tareas=['Tarea 1', 'Tarea 2'];
        // return $this->render('todo/index.html.twig', 
        // array('tareas' => $tareas));
        $todo =$this->getDoctrine()->getRepository(Todo::class)->findAll();

        return $this->render('todo/index.html.twig', 
        array('todos' => $todo));
    }


   /**
     * @Route("/todo/creacion", name="todo/creacion")
     * 
     */
    public function creacion(Request $request)
    {
        $todo = new Todo();
      
        $form = $this->createFormBuilder($todo)
        ->add('nombre', TextType::class, 
        array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
        ->add('categoria', TextType::class, 
        array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
        ->add('descripcion', TextareaType::class, 
        array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
         ->add('categoria', ChoiceType::class, 
         array('choices' => array('Tecnologia' => 'Tecnologia', 'Diseño' => 'Diseño', 'Salud'=>'Salud'), 'attr' => 
         array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
        ->add('fecha', DateType::class, 
        array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
        ->add('Save', SubmitType::class, 
        array('label'=> 'Crear', 
        'attr' => array('class' => 'btn btn-primary', 'style' => 'margin-bottom:15px')))
        ->getForm();
        
         $form->handleRequest($request);
         if($form->isSubmitted() &&  $form->isValid()){
            $nombre = $form['nombre']->getData();
            $categoria = $form['categoria']->getData();
            $descripcion = $form['descripcion']->getData();
             $fecha = $form['fecha']->getData();
            // $nombre = $form['nombre']->getData();
            
           // $now = new\DateTime('now');  
            
            $todo->setNombre($nombre);
            $todo->setCategoria($categoria);          
            $todo->setDescripcion($descripcion);                  
            $todo->setFecha($fecha);          
            //$todo->setCreateDate($now);    
            
            $sn = $this->getDoctrine()->getManager();      
            $sn -> persist($todo);
            $sn -> flush();
            
            $this->addFlash(
                'notice',
                'Todo Added'
            );
            return $this->redirectToRoute('todo');            
           
         }
        
        return $this->render('todo/creacion.html.twig', 
        array('form' => $form->createView() 
        ));
    }

    /**
     * @Route("/todo/{id}", name="todo_lista")
     */

    public function mostrar($id) 
    {
        $todo = $this->getDoctrine()->getRepository(Todo::class)->find($id);

        return $this->render('todo/mostrar.html.twig', 
        array('todo' => $todo));
    }

    /**
     * @Route("/todo/editar/{id}", name="todo_edit")
     */
    public function editar($id,Request $request)
    {
         //$now = new\DateTime('now');  
         $todo = $this->getDoctrine()->getRepository(Todo::class)->find($id);
        
            $todo->setNombre($todo->getNombre());
            $todo->setCategoria($todo->getCategoria());          
            $todo->setDescripcion($todo->getDescripcion());                  
            $todo->setFecha($todo->getFecha());          
            // $todo->setCreateDate($now);        
      
        $form = $this->createFormBuilder($todo)
        ->add('nombre', TextType::class, 
        array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
        ->add('categoria', TextType::class, 
        array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
        ->add('descripcion', TextareaType::class, 
        array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
        ->add('fecha', DateType::class, 
        array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
        ->add('Actualizar', SubmitType::class, 
        array('label'=> 'Actualizar', 'attr' => 
        array('class' => 'btn btn-primary', 'style' => 'margin-bottom:15px')))
        ->getForm();
        
        $form->handleRequest($request);
        if($form->isSubmitted() &&  $form->isValid()){
            $nombre = $form['nombre']->getData();
            $categoria = $form['categoria']->getData();
            $descripcion = $form['descripcion']->getData();
            $fecha = $form['fecha']->getData();
            
            
            $sn = $this->getDoctrine()->getManager();
            $todo = $sn->getRepository(Todo::class)->find($id);
            
            $todo->setNombre($nombre);
            $todo->setCategoria($categoria);          
            $todo->setDescripcion($descripcion);                  
            $todo->setFecha($fecha);          
                     
      
            $sn -> flush();
            
            $this->addFlash(
                'notice',
                'Actualizado'
            );
            return $this->redirectToRoute('todo');            
           
        }
        
        return $this->render('todo/editar.html.twig', array(
            'todo' => $todo,
            'form' => $form->createView()
        ));
        
    }

     /**
      * @Route("/todo/borrar/{id}", name="todo_delete")
      */
      public function borrar($id)
      {
          
           $sn = $this->getDoctrine()->getManager();
           $todo = $sn->getDoctrine()->getRepository(Todo::class)->find($id);
          
           $sn->remove($todo);
           $sn->flush();
          
           $this->addFlash(
                  'notice',
                  'Eliminado'
              );
            return $this->redirectToRoute('todo');             
      }

    //   public function borrar(Request $request, $id)
    //   {

    //     $todo = $this->getDoctrine()->getRepository
    //     (Todo::class)->find($id);

    //     $entityManager = $this->getDoctrine()->getManager();    
    //     $entityManager->remove($todo);
    //     $entityManager->flush();

    //         $respose = new Response();
    //         $respose->send();
    
    //   }

      /**
     * @Route("/todo/guardar")
     */
    // public function guardar()
    // {
    //     $entityManager = $this->getDoctrine()->getManager();

    //     $todo = new Todo();
    //     $todo->setNombre('Tarea Uno');
    //     $todo->setCategoria('Tecnologia');
    //     $todo->setDescripcion('loren isue');
       
    //      $todo->setFecha('');

    //     $entityManager->persist($todo);

    //     $entityManager->flush();

    //     return new Response('Guardada una tarea con el id '.$todo->getId());
    // }
}
