<?php
/**
 * Product controller.
 */

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\AuctionRepository;
use App\Repository\DealRepository;
use App\Repository\ProductRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * Class ProductController.
 *
 * @Route("/product")
 */
class ProductController extends AbstractController
{
    /**
     * Index action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request    HTTP request
     * @param \App\Repository\ProductRepository         $repository Product repository
     * @param \Knp\Component\Pager\PaginatorInterface   $paginator  Paginator
     * @param \Symfony\Component\Security\Core\Security $security   Security
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @Route(
     *     "/",
     *     name="product_index"
     * )
     */
    public function index(Request $request, ProductRepository $repository, PaginatorInterface $paginator, Security $security): Response
    {
        $user = $security->getUser();

        if ($security->isGranted('ROLE_ADMIN')) {
            $pagination = $paginator->paginate(
                $repository->queryAll(),
                $request->query->getInt('page', 1),
                Product::NUMBER_OF_ITEMS
            );
        } else {
            $pagination = $paginator->paginate(
                $repository->findBy(['user' => $user]),
                $request->query->getInt('page', 1),
                Product::NUMBER_OF_ITEMS
            );
        }

        return $this->render(
            'product/index.html.twig',
            ['pagination' => $pagination]
        );
    }

    /**
     * View action.
     *
     * @param \App\Entity\Product $product Product
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @Route(
     *     "/{id}",
     *     name="product_view",
     *     requirements={"id": "[1-9]\d*"}
     * )
     *
     * @IsGranted(
     *     "MANAGE",
     *     subject="product",
     * )
     */
    public function view(Product $product): Response
    {
        return $this->render(
            'product/view.html.twig',
            ['product' => $product]
        );
    }

    /**
     * New action.
     *
     * @param \Symfony\Component\Security\Core\Security $security   Security
     * @param \Symfony\Component\HttpFoundation\Request $request    HTTP request
     * @param \App\Repository\ProductRepository         $repository Product repository
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/new",
     *     methods={"GET", "POST"},
     *     name="product_new",
     * )
     */
    public function new(Security $security, Request $request, ProductRepository $repository): Response
    {
        $product = new Product();
        $user = $security->getUser();

        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $product->setUser($user);
            $repository->save($product);
            $this->addFlash('success', 'message.created_successfully');

            return $this->redirectToRoute('product_index');
        }

        return $this->render(
            'product/new.html.twig',
            ['form' => $form->createView()]
        );
    }

    /**
     * Edit action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request    HTTP request
     * @param \App\Entity\Product                       $product    Product entity
     * @param \App\Repository\ProductRepository         $repository Product repository
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/{id}/edit",
     *     methods={"GET", "PUT"},
     *     requirements={"id": "[1-9]\d*"},
     *     name="product_edit"
     * )
     *
     * @IsGranted(
     *     "MANAGE",
     *     subject="product",
     * )
     */
    public function edit(Request $request, Product $product, ProductRepository $repository): Response
    {
        $form = $this->createForm(ProductType::class, $product, ['method' => 'PUT']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $repository->save($product);
            $this->addFlash('success', 'message.updated_successfully');

            return $this->redirectToRoute('product_index');
        }

        return $this->render(
            'product/edit.html.twig',
            [
                'form' => $form->createView(),
                'product' => $product,
            ]
        );
    }

    /**
     * Delete action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request           HTTP request
     * @param \App\Entity\Product                       $product           Product entity
     * @param \App\Repository\ProductRepository         $repository        Product repository
     * @param \App\Repository\AuctionRepository         $auctionRepository Auction repository
     * @param \App\Repository\DealRepository            $dealRepository    Deal repository
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/{id}/delete",
     *     methods={"GET", "DELETE"},
     *     requirements={"id": "[1-9]\d*"},
     *     name="product_delete",
     * )
     *
     * @IsGranted(
     *     "MANAGE",
     *     subject="product",
     * )
     */
    public function delete(Request $request, Product $product, ProductRepository $repository, AuctionRepository $auctionRepository, DealRepository $dealRepository): Response
    {
        $form = $this->createForm(FormType::class, $product, ['method' => 'DELETE']);
        $form->handleRequest($request);

        if ($request->isMethod('DELETE') && !$form->isSubmitted()) {
            $form->submit($request->request->get($form->getName()));
        }

        $auctions = $auctionRepository->findBy(['product' => $product]);

        $deals = [];
        foreach ($auctions as $auction) {
            $deals[] = $dealRepository->findBy(['auction' => $auction]);
        }
        $deals = array_reduce($deals, 'array_merge', array());

        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($deals as $deal) {
                $dealRepository->delete($deal);
            }

            foreach ($auctions as $auction) {
                $auctionRepository->delete($auction);
            }

            $repository->delete($product);
            $this->addFlash('success', 'message.deleted_successfully');

            return $this->redirectToRoute('product_index');
        }

        return $this->render(
            'product/delete.html.twig',
            [
                'form' => $form->createView(),
                'product' => $product,
            ]
        );
    }
}
