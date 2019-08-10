<?php
/**
 * User type.
 */

namespace App\Form;

use App\Entity\Role;
use App\Entity\User;
use App\Repository\RoleRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class UserType.
 */
class UserType extends AbstractType
{
    /**
     * Builds the form.
     *
     * @see FormTypeExtensionInterface::buildForm()
     *
     * @param FormBuilderInterface $builder The form builder
     * @param array                $options The options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'role',
                EntityType::class,
                [
                    'label' => 'label.role',
                    'placeholder' => 'label.choose_role',
                    'class' => Role::class,
                    'query_builder' => function (RoleRepository $repo) {
                        return $repo->queryAll();
                    },
                    'choice_label' => 'roleName',
                ]
            )
            ->add(
                'login',
                TextType::class,
                [
                    'label' => 'label.login',
                    'required' => true,
                    'attr' => ['max_length' => 45],
                ]
            )
            ->add(
                'email',
                EmailType::class,
                [
                    'label' => 'label.email',
                    'required' => true,
                    'attr' => ['max_length' => 45],
                ]
            )
            ->add(
                'password',
                PasswordType::class,
                [
                    'label' => 'label.password',
                    'required' => true,
                    'attr' => ['max_length' => 128],
                ]
            );
    }

    /**
     * Configures the option for User type.
     *
     * @param OptionsResolver $resolver the resolver for the options
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => User::class]);
    }

    /**
     * Return the prefix of template block.
     *
     * @return string Prefix
     */
    public function getBlockPrefix(): string
    {
        return 'user';
    }
}
