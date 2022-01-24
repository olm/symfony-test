<?php


namespace App\Controller;


use App\Entity\Product;
use App\Repository\ProductRepository;
use App\Service\MgclClient;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{

    #[Route('/', name: 'app_index')]
    public function index(Request $request, ProductRepository $productRepository, PaginatorInterface $paginator): Response
    {
        $date = $request->query->get('date');
        $query = $productRepository->search($date);
        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            10
        );
        $count = count($pagination);
        return $this->render('index/index.html.twig', [
            'pagination' => $pagination,
            'count'      => $count,
        ]);
    }

    #[Route('/create', name: 'app_create')]
    public function create(Request $request, EntityManagerInterface $em, MgclClient $dateRetriever, Session $session): Response
    {
        $task = new Product($dateRetriever->getDateTime(), 'manual_hash');
        if (!$session->has('list')) {
            $list = $dateRetriever->showlist();
            $session->set('list', $list);
        }
        $form = $this->createFormBuilder($task)
            ->add('date', DateTimeType::class, [
                'disabled'     => true,
                'with_seconds' => true,
            ])
            ->add('hash', ChoiceType::class, [
                'choices' => $session->get('list')
            ])
            ->add('save', SubmitType::class)
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $task = $form->getData();
            $em->persist($task);
            $em->flush();

            $session->remove('list');
            return $this->redirectToRoute('app_index');
        }

        return $this->render('index/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}