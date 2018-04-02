<?php
/**
 * Created by PhpStorm.
 * User: kom
 * Date: 2018/03/15
 * Time: 5:19
 */

namespace Lattesoft\ApiMocroserviceCore\Service;


use Lattesoft\ApiMocroserviceCore\QDoctrine\QTaxonomy;
use Lattesoft\ApiMocroserviceCore\Response\IResponse;
use Lattesoft\ApiMocroserviceCore\Util\IPagination;

class TaxonomyService
{
    public static function getTaxonomyList($vocabulary, $perPage = 15)
    {

        $vocabulary = preg_match("/(.*?)(,)(.*?)/",$vocabulary) ? explode(",",$vocabulary) : ucfirst(strtolower($vocabulary));
        $taxonomy = QTaxonomy::getTaxonomyList($vocabulary);
        if ($perPage == "all") {
            $taxonomy = $taxonomy->getResult();
            if (count($taxonomy) > 0) {
                return IResponse::responseServiceWithData(2000501, $taxonomy);
            }
        } else {
            $taxonomy = IPagination::paginate($taxonomy, $perPage);
            if ($taxonomy->total() > 0) {
                return IResponse::responseServiceWithPagination(2000501, $taxonomy);
            }
        }

        return IResponse::responseService(4030501, $taxonomy);
    }
}