<?php
/**
 * Created by PhpStorm.
 * User: kom
 * Date: 2018/03/15
 * Time: 5:19
 */

namespace Finiz\Service;


use Finiz\QDoctrine\QTaxonomy;
use Finiz\Response\IResponse;
use Finiz\Util\IPagination;

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