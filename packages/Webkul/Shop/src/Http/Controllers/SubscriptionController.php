<?php

namespace Webkul\Shop\Http\Controllers;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;
use Webkul\Core\Repositories\SubscribersListRepository;

class SubscriptionController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(protected SubscribersListRepository $subscriptionRepository) {}

    /**
     * Subscribes email to the email subscription list
     *
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        $validator = Validator::make(request()->all(), [
            'email' => 'email|required',
            'g-recaptcha-response' => 'required',
        ]);

        //ddd(request()->all());

        if ($validator->fails()) {
            session()->flash('error', trans('shop::app.subscription.invalid-recaptcha'));

            return redirect()->back()->withErrors($validator)->withInput();
        }

        $email = request()->input('email');

        $subscription = $this->subscriptionRepository->findOneByField('email', $email);

        $recaptcha_secret = config('services.recaptcha.secret');


        $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret'   => $recaptcha_secret,
            'response' => request('g-recaptcha-response'),
        ]);
        


        $recaptcha = $response->json();


        if (! ($recaptcha['success'] ?? false)) {
            session()->flash('error', trans('shop::app.subscription.invalid-recaptcha'));

            return redirect()->back();
        }

        if ($subscription) {
            session()->flash('error', trans('shop::app.subscription.already'));

            return redirect()->back();
        }

        Event::dispatch('customer.subscription.before');

        $customer = auth()->user();

        $subscription = $this->subscriptionRepository->create([
            'email'         => $email,
            'channel_id'    => core()->getCurrentChannel()->id,
            'is_subscribed' => 1,
            'token'         => uniqid(),
            'customer_id'   => $customer->id ?? null,
        ]);

        if ($customer) {
            $customer->subscribed_to_news_letter = 1;

            $customer->save();
        }

        Event::dispatch('customer.subscription.after', $subscription);

        session()->flash('success', trans('shop::app.subscription.subscribe-success'));

        return redirect()->back();
    }

    /**
     * To unsubscribe from a the subscription list
     *
     * @param  string  $token
     * @return \Illuminate\Http\Response
     */
    public function destroy($token)
    {
        $this->subscriptionRepository->deleteWhere(['token' => $token]);

        session()->flash('success', trans('shop::app.subscription.unsubscribe-success'));

        return redirect()->route('shop.home.index');
    }
}
