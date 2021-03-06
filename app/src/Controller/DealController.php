<?php
/**
 * Deal controller.
 */

namespace App\Controller;

use App\Entity\Deal;
use App\Form\DealType;
use App\Repository\AuctionRepository;
use App\Repository\DealRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

/**
 * Class DealController.
 *
 * @Route(
 *     "/deal"
 * )
 */
class DealController extends AbstractController
{
    /**
     * Index action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request    HTTP request
     * @param \App\Repository\DealRepository            $repository Deal repository
     * @param \Knp\Component\Pager\PaginatorInterface   $paginator  Paginator
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @Route(
     *     "/",
     *     name="deal_index"
     * )
     */
    public function index(Request $request, DealRepository $repository, PaginatorInterface $paginator): Response
    {
        $pagination = $paginator->paginate(
            $repository->queryAll(),
            $request->query->getInt('page', 1),
            Deal::NUMBER_OF_ITEMS
        );

        return $this->render(
            'deal/index.html.twig',
            ['pagination' => $pagination]
        );
    }

    /**
     * View action.
     *
     * @param \App\Entity\Deal $deal Deal
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @Route(
     *     "/{id}",
     *     name="deal_view",
     *     requirements={"id": "[1-9]\d*"}
     * )
     */
    public function view(Deal $deal): Response
    {
        return $this->render(
            'deal/view.html.twig',
            ['deal' => $deal]
        );
    }

    /**
     * New action.
     *
     * @param \Symfony\Component\Security\Core\Security $security          Security
     * @param \Symfony\Component\HttpFoundation\Request $request           HTTP request
     * @param \App\Repository\DealRepository            $repository        Deal repository
     * @param \App\Repository\AuctionRepository         $auctionRepository Auction repository
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/{id}/new",
     *     methods={"GET", "POST"},
     *     name="deal_new",
     *     requirements={"id": "[1-9]\d*"}
     * )
     */
    public function new(Security $security, Request $request, DealRepository $repository, AuctionRepository $auctionRepository): Response
    {
        $id = $request->get('id');
        $user = $security->getUser();
        $auction = $auctionRepository->findOneBy(['id' => $id]);

        $deal = new Deal();
        $deal->setAuction($auction);

        $form = $this->createForm(DealType::class, $deal);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $deal->setUser($user);
            $deal->setDate(new \DateTime());
            $auctionRepository->newPrice($deal->getPrice(), $auction);

            $repository->save($deal);
            $this->addFlash('success', 'message.created_successfully');

            return $this->redirectToRoute('auction_view', ['id' => $id]);
        }

        return $this->render(
            'deal/new.html.twig',
            [
                'form' => $form->createView(),
                'auction' => $auction,
            ]
        );
    }

    /**
     * Edit action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request    HTTP request
     * @param \App\Entity\Deal                          $deal       Deal entity
     * @param \App\Repository\DealRepository            $repository Deal repository
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
     *     name="deal_edit"
     * )
     */
    public function edit(Request $request, Deal $deal, DealRepository $repository): Response
    {
        $form = $this->createForm(DealType::class, $deal, ['method' => 'PUT']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $repository->save($deal);
            $this->addFlash('success', 'message.updated_successfully');

            return $this->redirectToRoute('deal_index');
        }

        return $this->render(
            'deal/edit.html.twig',
            [
                'form' => $form->createView(),
                'deal' => $deal,
            ]
        );
    }

    /**
     * Delete action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request    HTTP request
     * @param \App\Entity\Deal                          $deal       Deal entity
     * @param \App\Repository\DealRepository            $repository Deal repository
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
     *     name="deal_delete",
     * )
     */
    public function delete(Request $request, Deal $deal, DealRepository $repository): Response
    {
        $form = $this->createForm(FormType::class, $deal, ['method' => 'DELETE']);
        $form->handleRequest($request);

        if ($request->isMethod('DELETE') && !$form->isSubmitted()) {
            $form->submit($request->request->get($form->getName()));
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $repository->delete($deal);
            $this->addFlash('success', 'message.deleted_successfully');

            return $this->redirectToRoute('deal_index');
        }

        return $this->render(
            'deal/delete.html.twig',
            [
                'form' => $form->createView(),
                'deal' => $deal,
            ]
        );
    }
}
