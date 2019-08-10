<?php
/**
 * Auction type.
 */

namespace App\Form;

use App\Entity\Auction;
use App\Entity\Product;
use App\Entity\User;
use App\Repository\ProductRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class AuctionType.
 */
class AuctionType extends AbstractType
{
    /**
     * User.
     *
     * @var \App\Entity\User User
     */
    private $user;

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
        $this->user = $options['user'];

        $builder
            ->add(
                'product',
                EntityType::class,
                [
                    'label' => 'label.product',
                    'placeholder' => 'label.choose_product',
                    'class' => Product::class,
                    'query_builder' => function (ProductRepository $repo) {
                        return $repo->queryByUser($this->user);
                    },
                    'choice_label' => 'productName',
                ]
            )
            ->add(
                'startPrice',
                MoneyType::class,
                [
                    'label' => 'label.start_price',
                    'required' => false,
                ]
            )
            ->add(
                'description',
                TextType::class,
                [
                    'label' => 'label.description',
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
        $resolver->setDefaults(
            [
                'data_class' => Auction::class,
                'user' => null,
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
        return 'auction';
    }
}
