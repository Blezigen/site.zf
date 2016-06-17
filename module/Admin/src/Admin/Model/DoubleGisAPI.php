<?php
/**
 * Created by PhpStorm.
 * User: Front
 * Date: 17.06.2016
 * Time: 10:57
 */

namespace Admin\Model;


class DoubleGisAPI
{
    private $_jData;
    private $_resultData;
    private $configuration;


    private function getOptions($id_category = 0, $page = 1)
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

    private function getURL($category, $page = 1)
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
            return json_decode($data);
        } else {
            if ($retry) {
                curl_close($ch);
                echo "Ошибка запроса, повтор запроса...\n";
                sleep(3);
                return $this->getContent($url);
            } else {
                echo "Ошибка запроса.\n";
                return false;
            }
        }
    }

    public function getFirmsByCategory($category_id){
        $this->_jData = $this->getContent($this->getURL($category_id));
        $this->_resultData = [];
        $page_count = ceil($this->_jData->results->firm->total / 20);
        echo $this->_jData->results->firm->total;
        for ($page_number = 1; $page_number <= $page_count; $page_number++) {
            $this->_jData = $this->getContent($this->getURL($category_id, $page_number));
            foreach ($this->_jData->results->firm->results as $single_firm) {
                $firm = array(
                    "id" => $single_firm->id,
                    "alias" => $single_firm->alias,
                    "name" => $single_firm->name,
                    "city" => $single_firm->city_name,
                    "contacts" => $this->getContacts($single_firm->contacts),
                    "rubrics"=>$this->getRubrics($single_firm->rubrics),
                );
                if (!$firm["contacts"]) { //если праила определенные в функции не пропустили контакты
                    unset($firm);
                    continue;
                }
                $this->_resultData[] = $firm;
            }
        }
        echo substr(" [filtred : ". $this->_resultData)."]";
        return $this->_resultData;
    }
    public function getContacts($contacts_array){

        $phones = [];
        foreach ($contacts_array as $contacts) {
            foreach ($contacts->contacts as $contact) {
                if ($contact->type == "phone") {
                    if(strlen($contact->rawdata)>10){
                        $phones[] = $contact->rawdata;
                    }
                }
                elseif($contact->type == "website") {
                    return false;
                }
            }
        }

        if (count($phones)<1){
            return false;
        }
        return array("phones"=>$phones);
    }
    public function getRubrics($rubrics_array){

        $rubrics = [];
        // print_r($contacts_array);
        foreach ($rubrics_array as $rubric) {
            $rubrics[] = [
                "id" => $rubric->id,
                "name" => $rubric->name,
                "parent_id" => $rubric->parent_id,
            ];
        }

        if (count($rubrics)<1){
            return false;
        }
        
        return $rubrics;
    }
}