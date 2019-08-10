<?php
/**
 * Product category controller.
 */

namespace App\Controller;

use App\Entity\ProductCategory;
use App\Form\ProductCategoryType;
use App\Repository\AuctionRepository;
use App\Repository\DealRepository;
use App\Repository\ProductCategoryRepository;
use App\Repository\ProductRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ProductCategoryController.
 *
 * @Route("/productCategory")
 */
class ProductCategoryController extends AbstractController
{
    /**
     * Index action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request    HTTP request
     * @param \App\Repository\ProductCategoryRepository $repository Product category repository
     * @param \Knp\Component\Pager\PaginatorInterface   $paginator  Paginator
     *
     * @return \Symfony\Component\HttpFoundation\Response Http response
     *
     * @Route(
     *     "/",
     *     name="product_category_index"
     * )
     */
    public function index(Request $request, ProductCategoryRepository $repository, PaginatorInterface $paginator): Response
    {
        $pagination = $paginator->paginate(
            $repository->queryAll(),
            $request->query->getInt('page', 1),
            ProductCategory::NUMBER_OF_ITEMS
        );

        return $this->render(
            'productCategory/index.html.twig',
            ['pagination' => $pagination]
        );
    }

    /**
     * View action.
     *
     * @param \App\Entity\ProductCategory $productCategory Product category
     *
     * @return \Symfony\Component\HttpFoundation\Response Http response
     *
     * @Route(
     *     "/{id}",
     *     name="product_category_view",
     *     requirements={"id": "[1-9]\d*"}
     * )
     */
    public function view(productCategory $productCategory): Response
    {
        return $this->render(
            'productCategory/view.html.twig',
            ['productCategory' => $productCategory]
        );
    }

    /**
     * New action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request    HTTP request
     * @param \App\Repository\ProductCategoryRepository $repository Product category repository
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/new",
     *     methods={"GET", "POST"},
     *     name="product_category_new",
     * )
     */
    public function new(Request $request, ProductCategoryRepository $repository): Response
    {
        $productCategory = new productCategory();
        $form = $this->createForm(ProductCategoryType::class, $productCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $repository->save($productCategory);
            $this->addFlash('success', 'message.created_successfully');

            return $this->redirectToRoute('product_category_index');
        }

        return $this->render(
            'productCategory/new.html.twig',
            ['form' => $form->createView()]
        );
    }

    /**
     * Edit action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request         HTTP request
     * @param \App\Entity\ProductCategory               $productCategory Product category entity
     * @param \App\Repository\ProductCategoryRepository $repository      Product category repository
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
     *     name="product_category_edit"
     * )
     */
    public function edit(Request $request, ProductCategory $productCategory, ProductCategoryRepository $repository): Response
    {
        $form = $this->createForm(ProductCategoryType::class, $productCategory, ['method' => 'PUT']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $repository->save($productCategory);
            $this->addFlash('success', 'message.updated_successfully');

            return $this->redirectToRoute('product_category_index');
        }

        return $this->render(
            'productCategory/edit.html.twig',
            [
                'form' => $form->createView(),
                'productCategory' => $productCategory,
            ]
        );
    }

    /**
     * Delete action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request           HTTP request
     * @param \App\Entity\ProductCategory               $productCategory   Product category entity
     * @param \App\Repository\ProductCategoryRepository $repository        Product category repository
     * @param \App\Repository\AuctionRepository         $auctionRepository Auction repository
     * @param \App\Repository\DealRepository            $dealRepository    Deal repository
     * @param \App\Repository\ProductRepository         $productRepository Product repository
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
     *     name="product_category_delete",
     * )
     */
    public function delete(Request $request, ProductCategory $productCategory, ProductCategoryRepository $repository, AuctionRepository $auctionRepository, DealRepository $dealRepository, ProductRepository $productRepository): Response
    {
        $form = $this->createForm(FormType::class, $productCategory, ['method' => 'DELETE']);
        $form->handleRequest($request);

        if ($request->isMethod('DELETE') && !$form->isSubmitted()) {
            $form->submit($request->request->get($form->getName()));
        }

        $products = $productRepository->findBy(['productCategory' => $productCategory]);

        $auctionsTemp = [];
        foreach ($products as $product) {
            $auctionsTemp[] = $auctionRepository->findBy(['product' => $product]);
        }
        $auctions = array_reduce($auctionsTemp, 'array_merge', array());

        $dealsTemp = [];
        foreach ($auctions as $auction) {
            $dealsTemp[] = $dealRepository->findBy(['auction' => $auction]);
        }
        $deals = array_reduce($dealsTemp, 'array_merge', array());

        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($deals as $deal) {
                $dealRepository->delete($deal);
            }

            foreach ($auctions as $auction) {
                $auctionRepository->delete($auction);
            }

            foreach ($products as $product) {
                $productRepository->delete($product);
            }

            $repository->delete($productCategory);
            $this->addFlash('success', 'message.deleted_successfully');

            return $this->redirectToRoute('product_category_index');
        }

        return $this->render(
            'productCategory/delete.html.twig',
            [
                'form' => $form->createView(),
                'productCategory' => $productCategory,
            ]
        );
    }
}
