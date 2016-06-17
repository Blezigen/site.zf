<?php

namespace Admin\Controller;

use Admin\Entity\ZfGparserFirm as EntityFirm;
use Admin\Entity\ZfGparserCategory as EntityCategory;
use Admin\Entity\ZfGparserContact as EntityContact;
use Admin\Entity\ZfGparserSubCategory as EntitySubCategory;
use Admin\Entity\ZfGparserFirmToSubCategory as EntityFtoc;
use Application\Controller\BaseController as BaseController;
use Admin\Model\DoubleGisAPI as API;
use Doctrine\ORM\Utility\IdentifierFlattener;

class ParserController extends BaseController
{
    private $_api;

    public function addSubCategory($parent_id,$data){
        $dc = $this->getDoctrine();
        foreach ($data as $item){
//            $query = $dc->createQuery('SELECT u FROM \Admin\Entity\ZfGparserSubCategory  u WHERE u.categoryExternalId = ' . $item->id);
//            if (count($query->getResult()) < 1) {
                try {
                    $subcategory = new EntitySubCategory();
                    $subcategory->setCategoryName($item->name);
                    $subcategory->setCategoryExternalId($item->id);
                    $subcategory->setIdGparserCategory($parent_id);
                    $dc->persist($subcategory);
                    $dc->flush();
                } catch (\Exception $e) {
                    echo "errror";
                }

//            }
        }
    }
    public function fillCategory($data){
        $dc = $this->getDoctrine();
        foreach ($data as $item) {
//            $query = $dc->createQuery('SELECT u FROM \Admin\Entity\ZfGparserCategory  u WHERE u.categoryExternalId = ' . $item->id);
//            if (count($query->getResult()) < 1) {
                try {
                    $category = new EntityCategory();
                    $category->setCategoryName($item->name);
                    $category->setCategoryExternalId($item->id);
                    $dc->persist($category);
                    $dc->flush();
                    $this->addSubCategory($category->getIdGparserCategory(), $item->children);
                } catch (\Exception $e) {
                    echo "errror";
                }

//            }
        }
    }

    public function insertFirms($firms,$ctegory_external_id){
        //var_dump($firms); die;
        foreach ($firms as $key => $single_firm) {
            $dc = $this->getDoctrine();
            $repository = $dc->getRepository('Admin\Entity\ZfGparserFirm');
            $firm = $repository->findOneByFirmExternalId($single_firm["id"]);

            if (!isset($firm)) {
                $firm = new EntityFirm();
                $firm->setFirmAlias($single_firm["alias"]);
                $firm->setFirmCity($single_firm["city"]);
                $firm->setFirmExternalId($single_firm["id"]);
                $firm->setFirmName($single_firm["name"]);
                $firm->setFirmInsertDate(new \DateTime("now"));
                try {
                    $dc->persist($firm);
                    $dc->flush();
                } catch (\Exception $ex) {
                    echo "Firm:" . $ex->getMessage() . "\n";
                }
            }

            foreach ($single_firm["contacts"] as $key => $contacts) {
                if ($key == "phones") {
                    foreach ($contacts as $contact) {
                        $repository = $dc->getRepository('Admin\Entity\ZfGparserContact');
                        $contactEntity = $repository->findOneBy(array(
                                "idGparserFirm" => $firm->getIdGparserFirm(),
                                "contactData" => $contact
                            )
                        );
                        //var_dump($has_contact); die;

                        if (!isset($contactEntity)) {
                            $contactEntity = new EntityContact();
                            $contactEntity->setIdGparserFirm($firm->getIdGparserFirm());
                            $contactEntity->setContactType("phone");
                            $contactEntity->setContactData($contact);
                            $contactEntity->setContactValue($contact);
                            try {
                                $dc->persist($contactEntity);
                                $dc->flush();
                            } catch (\Exception $ex) {
                                echo "Contact:" . $ex->getMessage();
                            }
                        }
                    }
                }
            }

            foreach ($single_firm["rubrics"] as  $rubrics) {
                $repositoryCategory = $dc->getRepository('Admin\Entity\ZfGparserCategory');
                $repositorySubCategory = $dc->getRepository('Admin\Entity\ZfGparserSubCategory');
                $repositoryFTSC = $dc->getRepository('Admin\Entity\ZfGparserFirmToSubCategory');

                $category = $repositoryCategory->findOneByCategoryExternalId($rubrics["parent_id"]);
                $sub_category = $repositorySubCategory->findOneByCategoryExternalId($rubrics["id"]);

                if (!isset($category)) {
                    $category = new EntityCategory();
                    $category->setCategoryExternalId($rubrics["parent_id"]);
                    $category->setCategoryName($rubrics["parent_id"]);
                    try {
                        $dc->persist($category);
                        $dc->flush();
                    } catch (\Exception $ex) {
                        echo "Category:" . $ex->getMessage() . "\n";
                    }
                }

                if (!isset($sub_category)) {
                    $sub_category = new EntitySubCategory();
                    $sub_category->setIdGparserCategory($category->getIdGparserCategory());
                    $sub_category->setCategoryExternalId($rubrics["id"]);
                    $sub_category->setCategoryName($rubrics["name"]);
                    try {
                        $dc->persist($sub_category);
                        $dc->flush();
                    } catch (\Exception $ex) {
                        echo "SubCategory:" . $ex->getMessage() . "\n";
                    }
                }

                $frmtosc = $repositoryFTSC->findOneBy(array(
                        "idGparserFirm" => $firm->getIdGparserFirm(),
                        "idGparserSubCategory" => $sub_category->getIdGparserSubCategory()
                    )
                );

                if (!isset($frmtosc)) {
                    $frmtosc = new EntityFtoc();
                    $frmtosc->setIdGparserFirm($firm->getIdGparserFirm());
                    $frmtosc->setIdGparserSubCategory($sub_category->getIdGparserSubCategory());
                    try {
                        $dc->persist($frmtosc);
                        $dc->flush();
                    } catch (\Exception $ex) {
                        echo "FirmToCategory:" . $ex->getMessage() . "\n";
                    }
                }
            }
        }
        return true;
    }

    public function getAllSubCategoryInCategory($extID){
        $dc = $this->getDoctrine();

        $repositoryCategory = $dc->getRepository('Admin\Entity\ZfGparserCategory');
        $repositorySubCategory = $dc->getRepository('Admin\Entity\ZfGparserSubCategory');

        $category = $repositoryCategory->findOneByCategoryExternalId($extID);

        if (!isset($category)){
            throw new \Exception("Не удалось получить id Родительской категории. (getAllSubCategoryInCategory)");
        }

        $sub_category = $repositorySubCategory->findByIdGparserCategory($category->getIdGparserCategory());

        if(!isset($sub_category)){
            throw new \Exception("Не удалось получить список категорий");
        }
//        $value = new EntitySubCategory();

        $result=[];
        foreach ($sub_category as $value) {
            $result[] = [
                "id" => $value->getCategoryExternalId(),
                "name" => $value->getCategoryName(),
            ];
        }

        return $result;
    }

    public function indexAction()
    {

        header( 'Content-type: text/html; charset=utf-8' );
        set_time_limit(0);
        $start = microtime(true);
        $this->_api = new API();
        ob_get_contents();
        header('charset="UTF-8"');
//        $results =  $this->_api->getContent("http://parselab.org/key/key.php?project=8&key=Rr0iM8GeMX9jUiCQqn73PiMHlxGvDlfcK2RvpRMiTw0jeGNJMp")->categories;
//        $this->fillCategory($results);
       // print_r($results);
//        foreach ($r as $category){
//
//        }
       // $contentJSON = json_decode($this->getContent($this->getLink("2955607514553869")));

        //print_r($contentJSON); die;
//        $this->_api = new API();
        $microOperation = microtime(true);
        $categorys = $this->getAllSubCategoryInCategory("2955607514546178");//array("2955607514553869","2955607514546585");
        echo 'Forming categories id:'.$categorys." заняло ".(microtime(true) - $microOperation).' сек. <br/>';
        flush();
        ob_flush();
        sleep(1);

          foreach ($categorys as $category) {
              $microOperation = microtime(true);
              echo "[Count : ";
            $result = $this->_api->getFirmsByCategory($category["id"]);
//              var_dump($result); die;
              echo ']   JSON OK:'.(microtime(true) - $microOperation).' сек. >>> ';
              flush();
              ob_flush();
              sleep(1);
//              header('Content-Type: text/plain; charset="UTF-8"');
//              print_r($result); die;
            $microOperation = microtime(true);
//              echo "\"".$category["name"]."\"";
            if($this->insertFirms($result, $category["id"])){
                //echo "Парсер работает";
            }
            echo " заняло [".(microtime(true) - $microOperation)." сек.] \"{$category["name"]}\" <br/>";
              flush();
              ob_flush();
              sleep(1);
        }
        echo 'Время выполнения всего скрипта: '.(microtime(true) - $start).' сек.';
    }
}
