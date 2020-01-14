<?php
namespace Sitation\CustomRuleEngineBundle\Model;

use Akeneo\Tool\Bundle\RuleEngineBundle\Model\ActionInterface;
use Akeneo\Pim\Automation\RuleEngine\Component\Model\FieldImpactActionInterface;

class ImageOperationAction implements ActionInterface, FieldImpactActionInterface
{
    const ACTION_TYPE = 'imageoperation';

    /** @var string */
    protected $field;

    /** @var array */
    protected $attributes = [];

    /** @var string */
    protected $imageoperation;

    /** @var array */
    protected $options = [];

    /**@var string*/
    protected $width;

    /**@var string*/
    protected $height;

    /**@var string*/
    protected $imageextension;

    /**
     * {@inheritdoc}
     */
    public function getField()
    {
        return $this->field;
    }

    /**
     * {@inheritdoc}
     */
    public function setField($field)
    {
        $this->field = $field;
    }

    /**
     * {@inheritdoc}
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * {@inheritdoc}
     */
    public function setOptions(array $options = [])
    {
        $this->options = $options;
    }

    /**
     * @return array
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * @param array $attributes
     */
    public function setAttributes(array $attributes = [])
    {
        $this->attributes = $attributes;
    }

    /**
     * @return string
     */
    public function getImageOperation()
    {
        return $this->imageoperation;
    }

    /**
     * @param string $imageoperation
     */
    public function setImageOperation($imageoperation)
    {
        $this->imageoperation = $imageoperation;
    }

    /**
     * @return string
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * @param string $width
     */
    public function setWidth($width)
    {
        $this->width = $width;
    }

    /**
     * @return string
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * @param string $height
     */
    public function setHeight($height)
    {
        $this->height = $height;
    }

    /**
     * @return string
     */
    public function getImageExtension()
    {
        return $this->imageextension;
    }

    /**
     * @param string $imageextension
     */
    public function setImageExtension($imageextension)
    {
        $this->imageextension = $imageextension;
    }

    /**
     * {@inheritdoc}
     */
    public function getImpactedFields()
    {
        return [$this->getField()];
    }
}