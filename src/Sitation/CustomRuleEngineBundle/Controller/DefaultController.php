<?php

namespace Sitation\CustomRuleEngineBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Akeneo\Tool\Component\FileStorage\File\FileStorer;
use Symfony\Component\Finder\SplFileInfo;
use Akeneo\Tool\Component\StorageUtils\Updater\PropertySetterInterface;
use Psr\Log\LoggerInterface;
#use Akeneo\Tool\Component\FileStorage\File\FileStorer;

class DefaultController extends Controller
{
    
    protected $propertySetter;
    protected $logger;
    protected $catalogstorage;
    protected $filestorer;
    /**
     * @param PropertySetterInterface $propertySetter
     */
    public function __construct(PropertySetterInterface $propertySetter,LoggerInterface $logger,FileStorer $filestorer)
    {
        $this->propertySetter = $propertySetter;
        $this->logger = $logger;
        $this->filestorer =$filestorer;
    } 

    public function indexAction()
    {
        return $this->render('SitationCustomRuleEngineBundle:Default:index.html.twig');
    }



    public function doImageConversion(Request $request){
    	 //$data = json_decode($request->getContent(), true);
    	 //print_r($data);
        try{
            	$data=array();
            	$data['productId'] =$request->get('productId');
            	$data['originalFileName'] =$request->get('originalFileName');
            	$data['filePath'] =$request->get('filePath');
                if(empty($data['productId'])){
                   return new Response("Inavlid Product Id given",200); 
                }
                if(empty($data['filePath'])){
                    return new Response("Invalid filePath given",200); 
                }
            	$data['resizePath']=$this->saveFileIntoStorage($data['filePath'],500,500,'jpg');
                if(empty($data['resizePath'])){
                    return new Response("Error in processing the resize image given.",200); 
                }
                $pqbFactory = $this->get('pim_catalog.query.product_query_builder_factory');
                $pqb = $pqbFactory->create(['default_locale' => null, 'default_scope' => null]);
                $pqb->addFilter('id', '=', $data['productId']);
                $productsCursor = $pqb->execute();
                $options = [];
                $options['locale']=null;
                $options['scope']=null;
                $saver = $this->get('pim_catalog.saver.product');
                $validator = $this->get('pim_catalog.validator.product');

                foreach ($productsCursor as $product) {
                        $violations = $validator->validate($product);
                        if (0 !== $violations->count()) {
                            /*throw new \Exception(sprintf(
                                'Impossible to setup test in %s: %s',
                                static::class,
                                $errors->get(0)->getMessage()
                            ));*/
                            continue;
                        }
                      $this->propertySetter->setData(
                            $product,
                            'image_2',
                            (string) $data['resizePath'],
                            $options
                        );
                    $saver->save($product);

                }
            	
            	return new JsonResponse(array('result'=>$productsCursor),200);
        }catch (Exception $exception) {
            throw new \Exception(
                sprintf('%s Exception Occured in doImageConversion method.', $exception->getMessage()),
                $exception
            );
        } 
    }

      public function saveFileIntoStorage($result,$width,$height,$imageextension){
         $filesystem = $this->getParameter('catalog_storage_dir');
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
}
