<?php
/**
 * Created by PhpStorm.
 * User: alekseylaptev
 * Date: 09.12.15
 * Time: 18:34
 */

namespace app\components\widgets;


use app\components\exceptions\AppHttpException;
use app\helpers\PHPDocHelper;
use yii\bootstrap\Widget;

class ErrorsList extends Widget
{

    public function run()
    {
        $errorConstants = (new \ReflectionClass('app\components\exceptions\AppHttpException'))->getConstants();
        $typeConstantsMap = $this->filterTypeConstantsMap($errorConstants);
        $idConstantsMap = $this->filterIdConstantsMap($errorConstants);
        $constantDescriptions = PHPDocHelper::getConstantComments('app\components\exceptions\AppHttpException');
        $idConstantsByTypeConstantValuesMap = $this->getIdConstantsByTypeConstantValuesMap($idConstantsMap);
        return $this->render('errorsList', [
            'typeConstantsMap' => $typeConstantsMap,
            'idConstantsMap' => $idConstantsMap,
            'idConstantsByTypeConstantValuesMap' => $idConstantsByTypeConstantValuesMap,
            'constantDescriptions' => $constantDescriptions
        ]);
    }

    /**
     * @param array $constants
     * @return array
     */
    private function filterTypeConstantsMap(array $constants)
    {
        $result = [];
        foreach ($constants as $const => $value) {
            if (substr($const, 0, 4) == 'TYPE') {
                $result[$const] = $value;
            }
        }
        return $result;
    }

    /**
     * @param array $constants
     * @return array
     */
    private function filterIdConstantsMap(array $constants)
    {
        $result = [];
        foreach ($constants as $const => $value) {
            if (substr($const, 0, 2) == 'ID') {
                $result[$const] = $value;
            }
        }
        return $result;
    }

    /**
     * @param array $idConstantsMap
     * @return array
     */
    private function getIdConstantsByTypeConstantValuesMap(array $idConstantsMap)
    {
        $result = [];
        $flipIdConstantsMap = array_flip($idConstantsMap);
        $exceptionMap = AppHttpException::getMap();
        foreach ($exceptionMap as $typeConstant => $data) {
            $constants = [];
            foreach ($data[1] as $value) {
                $constants[] = $flipIdConstantsMap[$value];
            }
            $result[$typeConstant] = $constants;
        }
        return $result;
    }

}