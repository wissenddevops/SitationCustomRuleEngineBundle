<?php

namespace Sitation\CustomRuleEngineBundle\Denormalizer\ProductRule;

use Sitation\CustomRuleEngineBundle\Model\ImageOperationAction;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;

class ImageOperationActionDenormalizer extends GetSetMethodNormalizer
{
    /**
     * {@inheritdoc}
     */
    public function denormalize($data, $class, $format = null, array $context = [])
    {
        return parent::denormalize($data, 'Sitation\CustomRuleEngineBundle\Model\ImageOperationAction');
    }

    /**
     * {@inheritdoc}
     */
    public function supportsDenormalization($data, $type, $format = null)
    {
        return isset($data['type']) && ImageOperationAction::ACTION_TYPE === $data['type'];
    }
}