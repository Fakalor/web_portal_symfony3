<?php
/**
 * Product category type.
 */

namespace App\Form;

use App\Entity\ProductCategory;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormTypeExtensionInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class ProductCategoryType.
 */
class ProductCategoryType extends AbstractType
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
        $builder->add(
            'categoryName',
            TextType::class,
            [
                'label' => 'label.category_name',
                'required' => true,
                'attr' => ['max_length' => 45],
            ]
        );
    }

    /**
     * Configures the option for Product category type.
     *
     * @param OptionsResolver $resolver The resolver for the options
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => ProductCategory::class]);
    }

    /**
     * Return the prefix of template block.
     *
     * @return string Prefix
     */
    public function getBlockPrefix(): string
    {
        return 'productCategory';
    }
}
