<?php
/**
 * Auction Controller.
 */

namespace App\Controller;

use App\Entity\Auction;
use App\Form\AuctionType;
use App\Repository\AuctionRepository;
use App\Repository\DealRepository;
use App\Repository\ProductCategoryRepository;
use App\Repository\ProductRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

/**
 * Class AuctionController.
 *
 * @Route("/")
 */
class AuctionController extends AbstractController
{
    /**
     * Index action.
     *
     * @param \Symfony\Component\Security\Core\Security $security                  Security
     * @param \App\Repository\AuctionRepository         $auctionRepository         Auction repository
     * @param \App\Repository\ProductCategoryRepository $productCategoryRepository Category repository
     * @param \App\Repository\ProductRepository         $productRepository         Product repository
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @Route(
     *     "/auction/",
     *     name="auction_index"
     * )
     */
    public function index(Security $security, AuctionRepository $auctionRepository, ProductCategoryRepository $productCategoryRepository, ProductRepository $productRepository): Response
    {
        $user = $security->getUser();

        if ($security->isGranted('ROLE_ADMIN')) {
            $auctions = $auctionRepository->findAll();
        } else {
            $auctionsTemp = [];
            $products = $productRepository->findBy(['user' => $user]);

            foreach ($products as $product) {
                $auctionsTemp[] = $auctionRepository->findBy(['product' => $product]);
            }

            $auctions = array_reduce($auctionsTemp, 'array_merge', array());
        }

        return $this->render(
            'auction/index.html.twig',
            [
                'auctions' => $auctions,
                'categories' => $productCategoryRepository->findAll(),
            ]
        );
    }

    /**
     * Start page action.
     *
     * @param \App\Repository\AuctionRepository         $auctionRepository         Auction repository
     * @param \App\Repository\ProductCategoryRepository $productCategoryRepository Category repository
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @Route(
     *     "/",
     *     name="auction_start_page"
     * )
     */
    public function startPage(AuctionRepository $auctionRepository, ProductCategoryRepository $productCategoryRepository): Response
    {
        return $this->render(
            'auction/startPage.html.twig',
            [
                'auctions' => $auctionRepository->findAll(),
                'categories' => $productCategoryRepository->findAll(),
            ]
        );
    }

    /**
     * View action.
     *
     * @param \App\Entity\Auction                       $auction                   Auction
     * @param \App\Repository\ProductCategoryRepository $productCategoryRepository Product category repository
     * @param \App\Repository\DealRepository            $dealRepository            Deal repository
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @Route(
     *     "/auction/{id}",
     *     name="auction_view",
     *     requirements={"id": "[1-9]\d*"}
     * )
     */
    public function view(Auction $auction, ProductCategoryRepository $productCategoryRepository, DealRepository $dealRepository): Response
    {
        return $this->render(
            'auction/view.html.twig',
            [
                'auction' => $auction,
                'categories' => $productCategoryRepository->findAll(),
                'deals' => $dealRepository->findBy(['auction' => $auction]),
            ]
        );
    }

    /**
     * View by category action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request                   Request
     * @param \App\Repository\AuctionRepository         $auctionRepository         Auction repository
     * @param \App\Repository\ProductCategoryRepository $productCategoryRepository Product category repository
     * @param \App\Repository\ProductRepository         $productRepository         Product repository
     * @param \Knp\Component\Pager\PaginatorInterface   $paginator                 Paginator
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @Route(
     *     "/auction/{categoryName}",
     *     name="auction_view_by_category",
     *     requirements={"categoryName": "^((?!new)[A-Za-z .])*$"}
     * )
     */
    public function viewByCategory(Request $request, AuctionRepository $auctionRepository, ProductCategoryRepository $productCategoryRepository, ProductRepository $productRepository, PaginatorInterface $paginator): Response
    {
        $categoryName = $request->get('categoryName');
        $category = $productCategoryRepository->findOneBy(['categoryName' => $categoryName]);
        $products = $productRepository->findBy(['productCategory' => $category]);

        $auctionsTemp = [];
        foreach ($products as $product) {
            $auctionsTemp[] = $auctionRepository->findBy(['product' => $product]);
        }

        $auctions = array_reduce($auctionsTemp, 'array_merge', array());

        $pagination = $paginator->paginate(
            $auctions,
            $request->query->getInt('page', 1),
            Auction::NUMBER_OF_ITEMS
        );

        return $this->render(
            'auction/viewByCategory.html.twig',
            [
                'pagination' => $pagination,
                'categories' => $productCategoryRepository->findAll(),
            ]
        );
    }

    /**
     * View user winning auctions.
     *
     * @param \Symfony\Component\Security\Core\Security $security          Security
     * @param \App\Repository\AuctionRepository         $auctionRepository Auction repository
     * @param \App\Repository\DealRepository            $dealRepository    Deal repository
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @throws \Exception
     *
     * @Route(
     *     "/winAuctions",
     *     name="auction_win",
     * )
     */
    public function winningAuction(Security $security, AuctionRepository $auctionRepository, DealRepository $dealRepository): Response
    {
        $user = $security->getUser();
        $auctions = $auctionRepository->findAll();
        $auctionsYouWin = [];
        $auctionWon = [];

        $now = new \DateTime('now');

        foreach ($auctions as $auction) {
            $deal = $dealRepository->findOneBy(['auction' => $auction], ['price' => 'DESC']);

            if (null !== $deal && $user === $deal->getUser()) {
                if ($auction->getEndDate() > $now) {
                    $auctionsYouWin[] = $auction;
                } else {
                    $auctionWon[] = $auction;
                }
            }
        }

        return $this->render(
            'auction/winAuctions.html.twig',
            [
                'auctionsYouWin' => $auctionsYouWin,
                'auctionWon' => $auctionWon,
            ]
        );
    }

    /**
     * New action.
     *
     * @param \Symfony\Component\Security\Core\Security $security   Security
     * @param \Symfony\Component\HttpFoundation\Request $request    HTTP request
     * @param \App\Repository\AuctionRepository         $repository Auction repository
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/auction/new",
     *     methods={"GET", "POST"},
     *     name="auction_new",
     * )
     */
    public function new(Security $security, Request $request, AuctionRepository $repository): Response
    {
        $auction = new Auction();
        $user = $security->getUser();

        $form = $this->createForm(AuctionType::class, $auction, ['user' => $user]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $auction->setStartDate(new \DateTime());
            $auction->setEndDate($auction->getStartDate()->modify('+2 weeks'));

            $repository->save($auction);
            $this->addFlash('success', 'message.created_successfully');

            return $this->redirectToRoute('auction_index');
        }

        return $this->render(
            'auction/new.html.twig',
            ['form' => $form->createView()]
        );
    }

    /**
     * Edit action.
     *
     * @param \Symfony\Component\Security\Core\Security $security   Security
     * @param \Symfony\Component\HttpFoundation\Request $request    HTTP request
     * @param \App\Entity\Auction                       $auction    Auction entity
     * @param \App\Repository\AuctionRepository         $repository Auction repository
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/auction/{id}/edit",
     *     methods={"GET", "PUT"},
     *     requirements={"id": "[1-9]\d*"},
     *     name="auction_edit"
     * )
     */
    public function edit(Security $security, Request $request, Auction $auction, AuctionRepository $repository): Response
    {
        $user = $security->getUser();

        $form = $this->createForm(AuctionType::class, $auction, ['method' => 'PUT', 'user' => $user]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $repository->save($auction);
            $this->addFlash('success', 'message.updated_successfully');

            return $this->redirectToRoute('auction_index');
        }

        return $this->render(
            'auction/edit.html.twig',
            [
                'form' => $form->createView(),
                'auction' => $auction,
            ]
        );
    }

    /**
     * Delete action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request        HTTP request
     * @param \App\Entity\Auction                       $auction        Auction entity
     * @param \App\Repository\AuctionRepository         $repository     Auction repository
     * @param \App\Repository\DealRepository            $dealRepository Deal repository
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/auction/{id}/delete",
     *     methods={"GET", "DELETE"},
     *     requirements={"id": "[1-9]\d*"},
     *     name="auction_delete",
     * )
     */
    public function delete(Request $request, Auction $auction, AuctionRepository $repository, DealRepository $dealRepository): Response
    {
        $form = $this->createForm(FormType::class, $auction, ['method' => 'DELETE']);

        $temp = $repository->findOneBy(['id' => $request->get('id')]);
        $deals = $dealRepository->findBy(['auction' => $temp]);

        $form->handleRequest($request);

        if ($request->isMethod('DELETE') && !$form->isSubmitted()) {
            $form->submit($request->request->get($form->getName()));
        }

        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($deals as $deal) {
                $dealRepository->delete($deal);
            }

            $repository->delete($auction);
            $this->addFlash('success', 'message.deleted_successfully');

            return $this->redirectToRoute('auction_index');
        }

        return $this->render(
            'auction/delete.html.twig',
            [
                'form' => $form->createView(),
                'auction' => $auction,
            ]
        );
    }
}
