<?php
/**
 * User contact details type.
 */

namespace App\Form;

use App\Entity\UserContactDetails;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class UserContactDetailsType.
 */
class UserContactDetailsType extends AbstractType
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
                'name',
                TextType::class,
                [
                    'label' => 'label.name',
                    'required' => true,
                    'attr' => ['max_length' => 45],
                ]
            )
            ->add(
                'surname',
                TextType::class,
                [
                    'label' => 'label.surname',
                    'required' => true,
                    'attr' => ['max_length' => 45],
                ]
            )
            ->add(
                'street',
                TextType::class,
                [
                    'label' => 'label.street',
                    'required' => true,
                    'attr' => ['max_length' => 100],
                ]
            )
            ->add(
                'postalCode',
                TextType::class,
                [
                    'label' => 'label.postal_code',
                    'required' => true,
                    'attr' => ['max_length' => 45],
                ]
            )
            ->add(
                'city',
                TextType::class,
                [
                    'label' => 'label.city',
                    'required' => true,
                    'attr' => ['max_length' => 45],
                ]
            )
            ->add(
                'country',
                TextType::class,
                [
                    'label' => 'label.country',
                    'required' => true,
                    'attr' => ['max_length' => 90],
                ]
            )
            ->add(
                'phoneNumber',
                TextType::class,
                [
                    'label' => 'label.phone_number',
                    'required' => true,
                    'attr' => ['max_length' => 9],
                ]
            );
    }

    /**
     * Configures the option for User type.
     *
     * @param OptionsResolver $resolver The resolver for the options
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => UserContactDetails::class]);
    }

    /**
     * Return the prefix of template block.
     *
     * @return string Prefix
     */
    public function getBlockPrefix(): string
    {
        return 'userContactDetails';
    }
}
