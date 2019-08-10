<?php
/**
 * Product type.
 */

namespace App\Form;

use App\Entity\Product;
use App\Entity\ProductCategory;
use App\Repository\ProductCategoryRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class ProductType.
 */
class ProductType extends AbstractType
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
                'productCategory',
                EntityType::class,
                [
                    'label' => 'label.product_category',
                    'placeholder' => 'label.choose_product_category',
                    'class' => ProductCategory::class,
                    'query_builder' => function (ProductCategoryRepository $repo) {
                        return $repo->queryAll();
                    },
                    'choice_label' => 'categoryName',
                ]
            )
            ->add(
                'productName',
                TextType::class,
                [
                    'label' => 'label.product_name',
                    'required' => true,
                    'attr' => ['max_length' => 45],
                ]
            )
            ->add(
                'description',
                TextType::class,
                [
                    'label' => 'label.description',
                    'required' => true,
                    'attr' => ['max_length' => 500],
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
        $resolver->setDefaults(['data_class' => Product::class]);
    }

    /**
     * Return the prefix of template block.
     *
     * @return string Prefix
     */
    public function getBlockPrefix(): string
    {
        return 'product';
    }
}
