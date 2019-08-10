<?php
/**
 * User controller.
 */

namespace App\Controller;

use App\Entity\User;
use App\Form\ChangePasswordType;
use App\Form\Model\ChangePassword;
use App\Form\UserType;
use App\Repository\AuctionRepository;
use App\Repository\DealRepository;
use App\Repository\ProductRepository;
use App\Repository\UserContactDetailsRepository;
use App\Repository\UserRepository;
use App\Repository\RoleRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * Class UserController.
 *
 * @Route("/user")
 */
class UserController extends AbstractController
{
    /**
     * Index action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request    HTTP request
     * @param \App\Repository\UserRepository            $repository User repository
     * @param \Knp\Component\Pager\PaginatorInterface   $paginator  Paginator
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @Route(
     *     "/",
     *     name="user_index"
     * )
     */
    public function index(Request $request, UserRepository $repository, PaginatorInterface $paginator): Response
    {
        $pagination = $paginator->paginate(
            $repository->queryAll(),
            $request->query->getInt('page', 1),
            User::NUMBER_OF_ITEMS
        );

        return $this->render(
            'user/index.html.twig',
            ['pagination' => $pagination]
        );
    }

    /**
     * View action.
     *
     * @param \App\Entity\User $user User
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @Route(
     *     "/{id}",
     *     name="user_view",
     *     requirements={"id": "[1-9]\d*"}
     * )
     *
     * @IsGranted(
     *     "MANAGE",
     *     subject="user",
     * )
     */
    public function view(User $user): Response
    {
        return $this->render(
            'user/view.html.twig',
            ['user' => $user]
        );
    }

    /**
     * Control panel for User.
     *
     * @param \Symfony\Component\Security\Core\Security    $security   Security
     * @param \App\Repository\UserContactDetailsRepository $repository User contact details repository
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @Route(
     *     "/controlPanel",
     *     name="user_control_panel"
     * )
     */
    public function controlPanel(Security $security, UserContactDetailsRepository $repository): Response
    {
        $user = $security->getUser();

        return $this->render(
            'user/controlPanel.html.twig',
            [
                'user' => $user,
                'details' => $repository->findOneBy(['user' => $user]),
            ]
        );
    }

    /**
     * Change role.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request        HTTP request
     * @param \App\Entity\User                          $user           User
     * @param \App\Repository\RoleRepository            $roleRepository RoleRepository
     * @param \App\Repository\UserRepository            $userRepository UserRepository
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/{id}/changeRole",
     *     name="user_change_role",
     *     requirements={"id": "[1-9]\d*"}
     * )
     */
    public function changeRole(Request $request, User $user, RoleRepository $roleRepository, UserRepository $userRepository): Response
    {
        $form = $this->createForm(FormType::class, null, ['method' => 'POST']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ('ROLE_ADMIN' === $user->getRole()->getRoleName()) {
                $role = $roleRepository->findOneBy(['roleName' => 'ROLE_USER']);
                $user->setRole($role);
            } else {
                $role = $roleRepository->findOneBy(['roleName' => 'ROLE_ADMIN']);
                $user->setRole($role);
            }
            $userRepository->save($user);

            $this->addFlash('success', 'message.updated_successfully');

            return $this->redirectToRoute('user_view', ['id' => $request->get('id')]);
        }

        return $this->render(
            'user/changeRole.html.twig',
            [
                'form' => $form->createView(),
                'user' => $user,
            ]
        );
    }

    /**
     * New action.
     *
     * @param \Symfony\Component\HttpFoundation\Request                             $request         HTTP request
     * @param \App\Repository\UserRepository                                        $repository      User repository
     * @param \Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface $passwordEncoder Password encoder
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/register",
     *     methods={"GET", "POST"},
     *     name="user_new",
     * )
     */
    public function new(Request $request, UserRepository $repository, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword($passwordEncoder->encodePassword($user, $user->getPassword()));
            $user->setWarning(0);
            $user->setBan(false);

            $repository->save($user);
            $this->addFlash('success', 'message.created_successfully');

            return $this->redirectToRoute('auction_start_page');
        }

        return $this->render(
            'user/new.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * Edit action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request    HTTP request
     * @param \App\Entity\User                          $user       User entity
     * @param \App\Repository\UserRepository            $repository User repository
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
     *     name="user_edit"
     * )
     * @IsGranted(
     *     "MANAGE",
     *     subject="user",
     * )
     */
    public function edit(Request $request, User $user, UserRepository $repository): Response
    {
        $form = $this->createForm(UserType::class, $user, ['method' => 'PUT']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $repository->save($user);
            $this->addFlash('success', 'message.updated_successfully');

            return $this->redirectToRoute('user_index');
        }

        return $this->render(
            'user/edit.html.twig',
            [
                'form' => $form->createView(),
                'user' => $user,
            ]
        );
    }

    /**
     * Change password.
     *
     * @param \Symfony\Component\Security\Core\Security                             $security        Security
     * @param \Symfony\Component\HttpFoundation\Request                             $request         HTTP request
     * @param \App\Repository\UserRepository                                        $repository      User repository
     * @param \Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface $passwordEncoder Password encoder
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/changePassword",
     *     methods={"GET","POST"},
     *     name="user_change_password"
     * )
     */
    public function editPassword(Security $security, Request $request, UserRepository $repository, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $changePassword = new ChangePassword();
        $user = $security->getUser();

        $form = $this->createForm(ChangePasswordType::class, $changePassword);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword($passwordEncoder->encodePassword($user, $changePassword->getNewPassword()));
            $repository->save($user);

            $this->addFlash('success', 'message.updated_successfully');

            return $this->redirectToRoute('user_control_panel');
        }

        return $this->render(
            'user/changePassword.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * Delete action.
     *
     * @param \Symfony\Component\HttpFoundation\Request    $request           HTTP request
     * @param \App\Entity\User                             $user              User entity
     * @param \App\Repository\UserRepository               $repository        User repository
     * @param \App\Repository\UserContactDetailsRepository $detailsRepository User contact details repository
     * @param \App\Repository\AuctionRepository            $auctionRepository Auction repository
     * @param \App\Repository\DealRepository               $dealRepository    Deal repository
     * @param \App\Repository\ProductRepository            $productRepository Product repository
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
     *     name="user_delete",
     * )
     */
    public function delete(Request $request, User $user, UserRepository $repository, UserContactDetailsRepository $detailsRepository, AuctionRepository $auctionRepository, DealRepository $dealRepository, ProductRepository $productRepository): Response
    {
        $form = $this->createForm(FormType::class, $user, ['method' => 'DELETE']);
        $form->handleRequest($request);

        if ($request->isMethod('DELETE') && !$form->isSubmitted()) {
            $form->submit($request->request->get($form->getName()));
        }

        $details = $detailsRepository->findOneBy(['user' => $user]);
        $userDeals = $dealRepository->findBy(['user' => $user]);
        $products = $productRepository->findBy(['user' => $user]);

        $auctionsTemp = [];
        foreach ($products as $product) {
            $auctionsTemp[] = $auctionRepository->findBy(['product' => $product]);
        }
        $auctions = array_reduce($auctionsTemp, 'array_merge', array());

        $dealsTemp = [];
        foreach ($auctions as $auction) {
            $dealsTemp[] = $dealRepository->findBy(['auction' => $auction]);
        }
        $dealsTemp = array_reduce($dealsTemp, 'array_merge', array());
        $deals = array_merge($userDeals, $dealsTemp);

        if ($form->isSubmitted() && $form->isValid()) {
            if (null !== $details) {
                $detailsRepository->delete($details);
            }

            foreach ($deals as $deal) {
                $dealRepository->delete($deal);
            }

            foreach ($auctions as $auction) {
                $auctionRepository->delete($auction);
            }

            foreach ($products as $product) {
                $productRepository->delete($product);
            }

            $repository->delete($user);
            $this->addFlash('success', 'message.deleted_successfully');

            return $this->redirectToRoute('user_index');
        }

        return $this->render(
            'user/delete.html.twig',
            [
                'form' => $form->createView(),
                'user' => $user,
            ]
        );
    }
}
