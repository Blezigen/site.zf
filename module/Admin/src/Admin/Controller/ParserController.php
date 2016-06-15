<?php

namespace Admin\Controller;

use Application\Controller\BaseController as BaseController;

class ParserController extends BaseController
{
    private function getOptions($id_category,$page = 1){
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
                    "filters" => array (
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
    private function getLink($category, $page = 1){
        $arr = $this->getOptions($category, $page)['parser'];
        $results = $arr["api_link"]
                .'?output='.$arr["output"]
                .'&key='.$arr["key"]
                ."&version=".$arr["version"]
                ."&lang=".$arr["lang"]
                ."&callback=".$arr["callback"]
                .'&criteria='.json_encode($arr['criteria']);
        return $results;
    }

    public function getContent($url, $retry = true)
    {
        $headers = array("User-Agent: Mozilla/5.0 (Windows NT 5.1; rv:24.0) Gecko/20100101 Firefox/24.0");

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt ($ch, CURLOPT_REFERER, $this->sReferer);
        curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt ($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt ($ch, CURLOPT_FAILONERROR, true);
        curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, true);
        //curl_setopt ($ch, CURLOPT_VERBOSE, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        if ($data = curl_exec($ch))
        {
            curl_close($ch);

            return $data;

        }
        else
        {
            if ($retry)
            {
                curl_close($ch);
                echo "Ошибка запроса, повтор запроса...\n";
                sleep(3);
                return $this->getUrl($url);
            }
            else
            {
                //echo "Request error.\n";
            }
        }
    }

    public function indexAction()
    {
        $results = array(
            //'text1' => "http://catalog.api.2gis.ru/advanced?criteria={\"what\":{\"id\":\"2955607514558342\",\"type\":\"rubric\",\"scope\":\"full\"},\"types\":[\"firm\"],\"sort\":\"relevance\",\"page\":1,\"filters\":{\"project_id\":21},\"magic\":{\"0\":\"advertising\",\"geom2cache\":\"org_mini\"}}&output=jsonp&key=ruffzo9376&version=1.3&lang=ru&callback=DG.Online.Utils.Ajax.callback.dga_10",
            'text2' => $this->getContent($this->getLink("2955607514558342")),
        );

        print_r(json_decode($results["text2"])); die;
        return $results;
    }
}
