<?php 

namespace common\components;

use CmsModule\Shop\common\models\Orders;
use CmsModule\Shop\common\models\OrderViewModel;
use CmsModule\Shop\common\models\PriceToWordModel;
use Dompdf\Dompdf;
use Yii;
use yii\base\Component;
use yii\base\View;


class InvoiceGeneratorComponent extends Component
{
    private Orders|null $order = null;

    public function __construct(
    ) {
    }

    public function fileNameInvoice(int $orderId): string 
    {
        $order = $this->order($orderId);
        return md5(sprintf('invoice_%s_%s', $order->id, $order->user_id)).'.pdf';
    }

    public function pathInvoice(int $orderId): string 
    {
        $file = $this->fileNameInvoice($orderId);
        return Yii::getAlias('@app/web/invoice/' . $file);
    }

    public function urlInvoice($orderId): string 
    {
        $file = $this->fileNameInvoice($orderId);
        return Yii::getAlias('@web/invoice/' . $file);
    }

    public function generate(int $orderId)
    {
        $dompdf = new Dompdf();
        $dompdf->loadHtml($this->getHtml($orderId));
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

        file_put_contents($this->pathInvoice($orderId), $dompdf->output()); 
    }

    private function order(int $orderId): Orders 
    {
        if(!$this->order) {
            $this->order = Orders::findOne($orderId);
        }

        return $this->order;
    }

    private function getHtml($orderId): string
    {
        $orderViewModel = Yii::createObject(OrderViewModel::class);
        $orderViewModel->order = $this->order($orderId);
        $user = $orderViewModel->order->user;
        $profile = $user->profile;
        $totalCount = $orderViewModel->getTotalCount();
        $totalPrice = $orderViewModel->getTotalPrice();
        $totalPriceWord = (new PriceToWordModel())->convert($totalPrice);

        $view = new View();
        return $view->renderFile(
            Yii::getAlias('@app/../common/view/invoice/template.php'),
            [
                'orderViewModel' => $orderViewModel,
                'totalCount' => $totalCount,
                'totalPrice' => $totalPrice,
                'totalPriceWord' => $totalPriceWord,
                'profile' => $profile,
                'base64ContentOfStamp' => $this->base64ContentOfStamp(),
            ]
        );
    }

    private function base64ContentOfStamp(): string 
    {
        $path = Yii::getAlias('@app/web/images/shamp.jpg');
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
        return $base64;
    }


}