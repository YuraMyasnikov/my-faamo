<?php

namespace frontend\models\shop\settings;

use CmsModule\Infoblocks\models\Infoblock;
use Yii;
use yii\base\Model;


class SettingsViewModel extends Model
{
    /**
     * @var array<string, ContactModel> 
     */
    private array $contacts = [];

    public function init()
    {
        parent::init();
        
        /**
         * @var Infoblock $infoblock
         */
        $infoblock = Infoblock::byCode('contacts');
        $items = $infoblock::find()
            ->where(['active' => true])
            ->orderBy(['sort' => SORT_DESC])
            ->asArray()
            ->all();

        foreach($items as $item) {
            $this->contacts[$item['code'] ?? ''] = new ContactModel([
                'code' => $item['code'],
                'name' => $item['name'], 
                'address' => $item['address'], 
                'phone' => $item['phone'], 
                'email' => $item['email'], 
                'working_hours' => $item['working_hours'], 
                'ya_map_widget' => $item['ya_map_widget']
            ]);
        }
        
        // $index = (int) array_search(Yii::$app->response->cookies->getValue('city'), array_column($this->contacts, 'code'));
        // $contacts = $items[$index] ?? null;
        // if($contacts) {
        //     $this->code         = $contacts['code']    ?? '';
        //     $this->name         = $contacts['name']    ?? '';
        //     $this->address      = $contacts['address'] ?? '';
        //     $this->phone        = $contacts['phone']   ?? '';
        //     $this->email        = $contacts['email']   ?? '';
        //     $this->workingHours = $contacts['working_hours'] ?? '';
        //     $this->yaMapWidget  = $contacts['ya_map_widget'] ?? '';
        // }

        // $cookies = Yii::$app->response->cookies;

        // $cookies->add(new Cookie([
        //     'name' => 'test1',
        //     'value' => 1,
        //     'domain' => '.test.dev',
        //     'expire' => time() + 180,
        // ]));
        // $cookies->add(new Cookie([
        //     'name' => 'test2',
        //     'value' => Yii::$app->request->get('loc'),
        //     'domain' => '.test.dev',
        //     'expire' => time() + 180,
        // ]));
        // $cookies->add(new Cookie([
        //     'name' => 'test3',
        //     'value' => date("H:i:s"),
        //     'domain' => '.test.dev',
        //     'expire' => time() + 180,
        // ]));

        // dd($cookies);

        // foreach($items as $infoblock) {
        //     /**
        //      * @var Infoblock $infoblock
        //      */
        //     $this->stdout(sprintf('- %s; %s; %s', $infoblock['fio'], $infoblock['email'], $infoblock['review_text']) . PHP_EOL);
        // }       
    }


    /**
     * @return array<ContactModel>
     */
    public function getAll(): array 
    {
        return array_values($this->contacts);
    }

    public function getActive(): ContactModel 
    {
        $index = (int) array_search(Yii::$app->session->get('city'), array_keys($this->contacts));
        $contact = array_values($this->contacts)[$index] ?? null;
        if(!$contact instanceof ContactModel) {
            return new ContactModel(['code' => '','name' => '', 'address' => '', 'phone' => '', 'email' => '', 'working_hours' => '', 'ya_map_widget' => '']);
        }
        return $contact;
    }

    public function getByCode(string $code): ContactModel
    {
        $index = (int) array_search($code, array_keys($this->contacts));
        $contact = array_values($this->contacts)[$index] ?? null;
        if(!$contact instanceof ContactModel) {
            return new ContactModel(['code' => '','name' => '', 'address' => '', 'phone' => '', 'email' => '', 'working_hours' => '', 'ya_map_widget' => '']);
        }
        return $contact;
    }

    public function getSocial(){
        return '';
    }
}

