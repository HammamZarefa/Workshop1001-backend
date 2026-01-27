<?php

namespace App\Http\Controllers\Api\v1;
use App\Http\Requests\PaymentRequest;
use App\Http\Resources\PaymentResource;
use App\Http\Traits\ApiResponses;
use App\Http\Controllers\Api\ApiController;
use App\Models\Payment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Events\OrderPaid;
use App\Models\Order;
use Illuminate\Support\Facades\Log;


class PaymentController extends ApiController
{
    use ApiResponses;


      //عرض جميع عمليات الدفع
public function index(Request $request)
{

    $perPage = $this->perPage($request);
    $payments = Payment::with('order')
        ->whereHas('order', function ($query) {
            $query->where('user_id', auth()->id());
        })
        ->latest()
        ->paginate($perPage);

    return $this->ok('Payments retrieved successfully', PaymentResource::collection($payments));

}



        //      إنشاء عملية دفع جديدة

   public function store(PaymentRequest $request)
{
    $data = $request->validated();

    $payment = DB::transaction(function () use ($data) {

        // 1️⃣ إنشاء عملية الدفع
        $payment = Payment::create($data);

        // 2️⃣ جلب الطلب المرتبط
        $order = Order::find($payment->order_id);

        if (! $order) {
            throw new \Exception('Order not found');
        }

        // 3️⃣ تحديث حالة الطلب
        $order->update([
            'status' => 'paid',
        ]);

        // 4️⃣ إطلاق الحدث 🔥 (خصم المخزون)
        event(new OrderPaid($order));

        return $payment;
    });

    return $this->success(
        'Payment created successfully',
        new PaymentResource($payment),
        201
    );
}


  //    عرض عملية دفع واحدة

   public function show($id)
{
    $payment = Payment::with('order')
        ->where('id', $id)
        ->whereHas('order', function ($query) {
            $query->where('user_id', auth()->id());
        })
        ->first();

    if (!$payment) {
        return $this->error('Payment not found or you do not have access', 404);
    }

    return $this->ok('Payment retrieved successfully', new PaymentResource($payment));
}



}
