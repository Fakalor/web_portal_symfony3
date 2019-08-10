<?php
/**
 * Change password form.
 */

namespace App\Form;

use App\Form\Model\ChangePassword;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class ChangePasswordType.
 */
class ChangePasswordType extends AbstractType
{
    /**
     * Builds the form.
     *
     * @see FormTypeExtensionInterface::buildForm()
     *
     * @param FormBuilderInterface $builder The form builder
     * @param array                $options The options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'oldPassword',
                PasswordType::class,
                [
                    'label' => 'label.old_password',
                    'required' => true,
                    'attr' => ['max_length' => 128],
                ]
            )
            ->add(
                'newPassword',
                RepeatedType::class,
                [
                    'type' => PasswordType::class,
                    'invalid_message' => 'Passwords must be identical',
                    'options' => ['attr' => ['max_length' => 128]],
                    'required' => true,
                    'first_options' => ['label' => 'label.new_password'],
                    'second_options' => ['label' => 'label.new_password_repeat'],
                ]
            );
    }

    /**
     * Configures the option for Product category type.
     *
     * @param OptionsResolver $resolver the resolver for the options
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'data_class' => ChangePassword::class,
            ]
        );
    }

    /**
     * Return the prefix of template block.
     *
     * @return string Prefix
     */
    public function getBlockPrefix(): string
    {
        return 'changePassword';
    }
}
