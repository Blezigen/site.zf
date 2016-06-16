<?php

namespace Admin\Controller;

use Admin\Entity\ZfGparserFirm as EntityFirm;
use Admin\Entity\ZfGparserCategory as EntityCategory;
use Admin\Entity\ZfGparserContact as EntityContact;
use Admin\Entity\ZfGparserSubCategory as EntitySubCategory;
use Application\Controller\BaseController as BaseController;

class ParserController extends BaseController
{
    private function getOptions($id_category, $page = 1)
    {
        return array(
            'parser' => array(
                "api_link" => "http://catalog.api.2gis.ru/advanced",

                "criteria" => array(
                    "what" => array(
                        "id" => $id_category,//"2955607514558342",
                        "type" => "rubric",
                        "scope" => "full"
                    ),
                    "types" => array("firm"), // Выборка по фирмам
                    "sort" => "relevance",
                    "page" => $page,
                    "filters" => array(
                        "project_id" => 21 //Казань
                    ),
                    "magic" => array(
                        "0" => "advertising",
                        "geom2cache" => "org_mini"
                    ),
                ),
                "output" => "json",
                "key" => "ruffzo9376",
                "version" => "1.3",
                "lang" => "ru",
                "callback" => "DG.Online.Utils.Ajax.callback.dga_10"
            ),
        );
    }

    private function getLink($category, $page = 1)
    {
        $arr = $this->getOptions($category, $page)['parser'];
        $results = $arr["api_link"]
            . '?output=' . $arr["output"]
            . '&key=' . $arr["key"]
            . "&version=" . $arr["version"]
            . "&lang=" . $arr["lang"]
            . "&callback=" . $arr["callback"]
            . '&criteria=' . json_encode($arr['criteria']);
        return $results;
    }

    public function getContent($url, $retry = true)
    {
        $headers = array("User-Agent: Mozilla/5.0 (Windows NT 5.1; rv:24.0) Gecko/20100101 Firefox/24.0");

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_REFERER, $this->sReferer);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_FAILONERROR, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        //curl_setopt ($ch, CURLOPT_VERBOSE, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        if ($data = curl_exec($ch)) {
            curl_close($ch);

            return $data;

        } else {
            if ($retry) {
                curl_close($ch);
                echo "Ошибка запроса, повтор запроса...\n";
                sleep(3);
                return $this->getUrl($url);
            } else {
                //echo "Request error.\n";
            }
        }
    }

    public function inserCategorys(){

    }

    public function addSubCategory($parent_id,$data){
        $dc = $this->getDoctrine();
        foreach ($data as $item){
            $query = $dc->createQuery('SELECT u FROM \Admin\Entity\ZfGparserSubCategory  u WHERE u.categoryExternalId = ' . $item->id);
            if (count($query->getResult()) < 1) {
                try {
                    $category = new EntitySubCategory();
                    $category->setCategoryName($item->name);
                    $category->setCategoryExternalId($item->id);
                    $category->setIdGparserCategory($parent_id);
                    $dc->persist($category);
                    $dc->flush();
                } catch (\Exception $e) {
                    echo "errror";
                }

            }
        }
    }

    public function fillCategory($data){
        $dc = $this->getDoctrine();
        foreach ($data as $item) {
            $query = $dc->createQuery('SELECT u FROM \Admin\Entity\ZfGparserCategory  u WHERE u.categoryExternalId = ' . $item->id);
            if (count($query->getResult()) < 1) {
                try {
                    $category = new EntityCategory();
                    $category->setCategoryName($item->name);
                    $category->setCategoryExternalId($item->id);
                    $dc->persist($category);
                    $dc->flush();
                    $this->addSubCategory($item->id, $item->children);
                } catch (\Exception $e) {
                    echo "errror";
                }

            }
        }
    }

    public function fillFirmByCategory($category_id){
        $contentJSON = json_decode($this->getContent($this->getLink($category_id)));

        $totalPages = ceil($contentJSON->results->firm->total / 20);

//        if ($totalPages > 1) {
            for ($i = 1; $i <= $totalPages; $i++) {
                $contentJSON = json_decode($this->getContent($this->getLink($category_id, $i)));
                foreach ($contentJSON->results->firm->results as $single_firm) {
                    //print_r($single_firm);
                    $dc = $this->getDoctrine();
                    $query = $dc->createQuery('SELECT u FROM \Admin\Entity\ZfGparserFirm  u WHERE u.firmExternalId = ' . $single_firm->id);
                    if (count($query->getResult()) < 1) {

                        try {
                            $firm = new EntityFirm();
                            $firm->setFirmAlias($single_firm->alias);
                            $firm->setFirmCity($single_firm->city_name);
                            $firm->setFirmExternalId($single_firm->id);
                            $firm->setFirmName($single_firm->name);
                            $firm->setFirmInsertDate(new \DateTime("now"));
                            $contacts = $this->fillContactByFirm($single_firm->contacts);
                            if($contacts != false){
                                $dc->persist($firm);
                                $dc->flush();
                                $query = $dc->createQuery('SELECT u FROM \Admin\Entity\ZfGparserFirm  u WHERE u.firmExternalId = ' . $single_firm->id);
                                $firms_id = $query->getResult()[0]->getIdGparserFirm();
                                foreach ($contacts["phones"] as $contact) {
                                    $contactEntity = new EntityContact();
                                    $contactEntity->setIdGparserFirm($firms_id);
                                    $contactEntity->setContactType("phone");
                                    $contactEntity->setContactData($contact);
                                    $contactEntity->setContactValue($contact);
                                    $contactEntity->getIdGparserFirm();
//                                    print_r($firms_id); die;
                                    $hasContact = $dc->createQuery('SELECT u FROM \Admin\Entity\ZfGparserContact  u WHERE u.contactData = ' . $contact .' and u.idGparserContact = ' . $firms_id);
                                    if (count($hasContact->getResult()) < 1) {
                                        try {
                                            $dc->persist($contactEntity);
                                            $dc->flush();
                                        } catch (\Exception $e) {
                                            echo "errror<br/>"; die;
                                        }
                                    }
                                    else continue;
                                }
                            }
                            else continue;
                        } catch (\Exception $e) {
                            echo "errror2"; die;
                        }
                    }

                }
//            }
            //return $this->redirect()->toRoute('admin/category');

            //return $results;
        }
    }
    public function fillContactByFirm($contacts_array){

        $phones = array();

       // print_r($contacts_array);
        foreach ($contacts_array as $contacts) {
            foreach ($contacts->contacts as $contact) {
                if ($contact->type == "phone") {
                    $phones[] = $contact->rawdata;
                }
                elseif($contact->type == "website") {
                    return false;
                }
            }
        }

//        var_dump($phones);die;
        return array("phones"=>$phones);
    }

    public function indexAction()
    {
        $results =  json_decode($this->getContent("http://parselab.org/key/key.php?project=8&key=Rr0iM8GeMX9jUiCQqn73PiMHlxGvDlfcK2RvpRMiTw0jeGNJMp"))->categories;
        //$this->fillCategory($results);
       // print_r($results);
//        foreach ($r as $category){
//
//        }
        $contentJSON = json_decode($this->getContent($this->getLink("2955607514553869")));

        //print_r($contentJSON); die;
        $categorys = array("2955607514553869","2955607514546585");

        foreach ($categorys as $category) {
            $this->fillFirmByCategory($category);
        }
    }
}
