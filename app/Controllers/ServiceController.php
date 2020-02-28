<?php

namespace App\Controllers;

use Fxiao\LumenTools\Controller;
use App\Service\Pwt;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function sms(Request $request)
    {
        $pwt = new Pwt;

        $pwtp = $request->headers->get('pwtp', null);
        $phone = $request->get('phone', null);

        $pwt->verify($pwtp, $request->get('uri', null))
            ->verify($pwtp, $phone);

        return $this->response->accepted(null, $pwt->sms($phone));
    }

    public function uri(Request $request)
    {
        $pwt = new Pwt;

        $pwtu = $pwt->setUri($request->get('uri', null))->generate(); 

        return $this->response->created(null, ['pwtu' => $pwtu]);
    }

    public function phone(Request $request)
    {
        $pwt = new Pwt;

        $pwtp = $pwt->verify($request->headers->get('pwtu', null), $request->get('uri', null))
            ->setPhone($request->get('phone', null))
            ->generate(); 

        return $this->response->created(null, ['pwtp' => $pwtp]);
    }
}
