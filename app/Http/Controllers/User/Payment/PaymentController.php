<?php

namespace App\Http\Controllers\User\Payment;

use App\Http\Controllers\BaseController;

class PaymentController extends BaseController
{
    //
    protected $booking, $bookingPayment, $userCardDetail;

    public function __construct()
    {
        parent::__construct();
        $this->booking        = $this->bookingRepo;
        $this->bookingPayment = $this->bookingPaymentRepo;
        $this->userCardDetail = $this->userCardDetailRepo;
    }

    public function pay($bookingId)
    {
        $getRequest = $this->httpRequest->all();
        $getBooking = $this->booking->getWhereFirst('id', $bookingId);

        if (!empty($getBooking)) {
            $getRequest['user_id']    = $getBooking->user_id;
            $getRequest['booking_id'] = $bookingId;

            // Save user card details.
            $saveUserCardDetails   = $this->userCardDetail->create($getRequest);

            if ($saveUserCardDetails->status() == 200) {
                // Save copy with id.
                if (!empty($getRequest['copy_with_id'])) {
                    $saveCopyWithId = $this->booking->updateBooking($bookingId, ['copy_with_id' => $getRequest['copy_with_id']]);

                    if ($saveCopyWithId->status() == 200) {
                        return $this->bookingPayment->create($getRequest);
                    }
                }
            }
        }

        return response()->json([
            'code' => 401,
            'msg'  => 'Booking not found.'
        ]);
    }
}
