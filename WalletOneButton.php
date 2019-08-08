<?php

namespace den11100\walletone;

use yii\base\Exception;
use yii\base\Widget;

class WalletOneButton extends Widget
{
    use TWalletOne;

    /**
     * @var string
     */
    public $label;

    /**
     * @var array
     */
    public $buttonOptions = ['class'=>'btn btn-info'];

    public $component = 'walletone';

    public function init(){
        parent::init();

        try{
            /** @var self $component */
            $component = \Yii::$app->{$this->component};
            if($component){
                if(!$this->secretKey && $component->secretKey){
                    $this->secretKey = $component->secretKey;
                }
                if($component->walletOptions){
                    $this->walletOptions = self::array_merge_recursive_distinct($component->walletOptions, $this->walletOptions);
                }
                
                if($component->buttonLabel){
                    $this->label = $component->buttonLabel;
                }

                if($component->buttonOptions){
                    $this->buttonOptions = self::array_merge_recursive_distinct($component->buttonOptions, $this->buttonOptions);
                }

                if(!$this->signatureMethod && $component->signatureMethod){
                    $this->signatureMethod = $component->signatureMethod;
                }
            }
        }catch (Exception $c){}

        if(!$this->signatureMethod){
            $this->signatureMethod = WalletOne::SIGNATURE_SHA1;
        }

        if(!$this->label){
            $this->label = 'WalletOne Play';
        }
    }

    public function run()
    {
        \Yii::$app->request->enableCsrfValidation = false;

        $formData = $this->getFields();

        return $this->render('form',[
            'formData' => $formData
        ]);
    }

}
