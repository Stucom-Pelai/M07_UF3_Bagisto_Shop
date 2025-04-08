@component('shop::emails.layout')
@slot('header')
<h1 style="text-align: center; color: #333;">Price Drop Alert!</h1>
@endslot

<p style="font-size: 16px; color: #555;">
    Hello,
</p>

<p style="font-size: 16px; color: #555;">
    Great news! The price of <strong>{{ $product->name }}</strong> has dropped. Don't miss this opportunity to grab it at a
    discounted price.
</p>

<p style="font-size: 14px; color: #999; margin-top: 20px;">
    Thank you for shopping with us!
</p>
@endcomponent