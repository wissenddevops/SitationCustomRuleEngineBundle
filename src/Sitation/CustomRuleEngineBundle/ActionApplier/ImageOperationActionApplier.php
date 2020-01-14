<?php

namespace Sitation\CustomRuleEngineBundle\ActionApplier;

use Akeneo\Tool\Bundle\RuleEngineBundle\Model\ActionInterface;
use Sitation\CustomRuleEngineBundle\Model\ImageOperationAction;
use Akeneo\Tool\Component\RuleEngine\ActionApplier\ActionApplierInterface;
use Akeneo\Tool\Component\StorageUtils\Updater\PropertySetterInterface;
use Psr\Log\LoggerInterface;
use Akeneo\Tool\Component\FileStorage\File\FileStorer;
use Symfony\Component\Finder\SplFileInfo;

class ImageOperationActionApplier implements ActionApplierInterface
{
    /** @var PropertySetterInterface */
    protected $propertySetter;
    private $logger;
    private $catalogstorage;
    private $filestorer;
    /**
     * @param PropertySetterInterface $propertySetter
     */
    public function __construct(PropertySetterInterface $propertySetter,LoggerInterface $logger,$catalogstorage,FileStorer $filestorer)
    {
        $this->propertySetter = $propertySetter;
        $this->logger = $logger;
        $this->catalogstorage = $catalogstorage;
        $this->filestorer =$filestorer;
    }

    /**
     * {@inheritdoc}
     */
    public function applyAction(ActionInterface $action, array $products = [])
    {
        //$logger = new LoggerInterface(); 
        $attributes         = $action->getAttributes();
        $operationtype      = $action->getImageOperation();
        $width              = $action->getWidth();
        $height              = $action->getHeight();
        $imageextension     = $action->getImageExtension();

        foreach ($products as $product) {
            if($operationtype == 'resize'){
                 $this->logger->info("Checking for value from products attribute code : ".json_encode($attributes[0]));
                $value = $product->getValue($attributes[0]);
                 $this->logger->info("Image value from products".$value);
                 $this->logger->info("width".$width);
                 $this->logger->info("height".$height);
                 $this->logger->info("imageextension".$imageextension);
                 $this->logger->info("operationtype".$operationtype);
                if(!empty($value)){
                    $result=$this->saveFileIntoStorage((string) $value,$width,$height,$imageextension);
                 $this->logger->info("Image result from products to set ".$result);
                    $this->propertySetter->setData(
                            $product,
                            $action->getField(),
                            $result,
                            $action->getOptions()
                        );
                }
            } 
        }
    }

    public function saveFileIntoStorage($result,$width,$height,$imageextension){
         $filesystem = $this->catalogstorage;
         $targetFilePath=$filesystem.'/'.$result;
         $imagick = new \Imagick($targetFilePath);
         $imagick->resizeImage($width, $height, \Imagick::FILTER_LANCZOS,1, true);
         $destinationFileNameArray=explode(".",$result);
         array_pop($destinationFileNameArray);
         $destinationFileName=implode('',$destinationFileNameArray);
         $imagick->writeImage($filesystem.'/'.$destinationFileName."_500X500.".$imageextension);
         $result =(string) $destinationFileName."_500X500.".$imageextension;
         $originalFilenameArray=explode("/",$result);
         $originalFilename=$originalFilename[count($originalFilename)-1];
         $uploadedFile = new SplFileInfo($filesystem.'/'.$destinationFileName."_500X500.".$imageextension,$filesystem.'/'.$destinationFileName."_500X500.".$imageextension,$originalFilename);
         $savedFile=$this->filestorer->store($uploadedFile, 'catalogStorage');
         return $savedFile->getKey();
    }

    /**
     * {@inheritdoc}
     */
    public function supports(ActionInterface $action)
    {
        return $action instanceof ImageOperationAction;
    }
}