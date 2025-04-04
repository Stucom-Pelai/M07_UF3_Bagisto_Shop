<p align="center">
    <a href="http://www.bagisto.com"><img src="https://bagisto.com/wp-content/themes/bagisto/images/logo.png" alt="Total Downloads"></a>
</p>
<p align="center">
    ➡️ <a href="https://bagisto.com/en/">Website</a> | <a href="https://devdocs.bagisto.com/">Documentation</a> ⬅️
</p>

<p align="center" style="display: inline;">
    <img class="flag-img" src="https://flagicons.lipis.dev/flags/4x3/ar.svg" alt="Arabic" width="24" height="24">
    <img class="flag-img" src="https://flagicons.lipis.dev/flags/4x3/de.svg" alt="German" width="24" height="24">
    <img class="flag-img" src="https://flagicons.lipis.dev/flags/4x3/us.svg" alt="English" width="24" height="24">
    <img class="flag-img" src="https://flagicons.lipis.dev/flags/4x3/es.svg" alt="Spanish" width="24" height="24">
    <img class="flag-img" src="https://flagicons.lipis.dev/flags/4x3/ir.svg" alt="Persian" width="24" height="24">
    <img class="flag-img" src="https://flagicons.lipis.dev/flags/4x3/it.svg" alt="Italian" width="24" height="24">
    <img class="flag-img" src="https://flagicons.lipis.dev/flags/4x3/nl.svg" alt="Dutch" width="24" height="24">
    <img class="flag-img" src="https://flagicons.lipis.dev/flags/4x3/pl.svg" alt="Polish" width="24" height="24">
    <img class="flag-img" src="https://flagicons.lipis.dev/flags/4x3/pt.svg" alt="Portuguese" width="24" height="24">
    <img class="flag-img" src="https://flagicons.lipis.dev/flags/4x3/tr.svg" alt="Turkish" width="24" height="24">
    <img class="flag-img" src="https://flagicons.lipis.dev/flags/4x3/eg.svg" alt="Egyptian" width="24" height="24">
    <img class="flag-img" src="https://flagicons.lipis.dev/flags/4x3/cn.svg" alt="Chinese" width="24" height="24">
</p>

# Introduction
Bagisto is an opensource [laravel eCommerce](https://www.bagisto.com/) framework built on some of the hottest technologies such as [Laravel](https://laravel.com/) (a [PHP](https://secure.php.net/) framework) and [Vue.js](https://vuejs.org/) a progressive Javascript framework.

Bagisto can help you cut down your time, cost, and workforce for building online stores or migrating from physical stores to the ever-demanding online world. Your business—whether small or huge—can benefit. The best part, it's straightforward to set it up!


# Installation
1. Clone the repository:

```bash
   git clone 
```

2. Install Composer dependencies:

```bash
composer install
```

3. Copy the example enviroment file 

```bash
cp .env.example .env
```

4. Create the database using the Artisan command:

```bash
php artisan db:create bagisto
```
Then, configure the .env file with the database name: DB_DATABASE=bagisto

5. Generate an application key

```bash
php artisan key:generate
```

6. Create a symbolic link from 'public/storage' to 'storage/app/public'

```bash
php artisan storage:link
```

7. Configuration, assets (CSS, js, images, etc.) and all necessary files will be copied to the specified publish location.

```bash
php artisan vendor:publish
```
 -> Press 0 and then press enter to publish all assets and configurations.
 
8. Run migrations and seed the database

```bash
php artisan migrate:fresh --seed
```

9. Start the Laravel development server 

```bash
php artisan serve
```

# Headless Commerce

The power of headless laravel commerce now comes to Bagisto enabling you to experience seamless and easily scalable storefront performance. Backed by some of the hottest tech stacks (Vue and React), Bagisto commerce can now be used to build powerful headless commerce solutions offering blazing-fast speed and easy customization powered by Vue Storefront and Next.js

## Vue Storefront

![enter image description here](https://raw.githubusercontent.com/bagisto/temp-media/master/vue.png)

Vue Storefront 2 integration for Bagisto: [https://github.com/bagisto/vuestorefront](https://github.com/bagisto/vuestorefront)

## Next.js Commerce

![enter image description here](https://raw.githubusercontent.com/bagisto/temp-media/master/next.png)

Develop and deploy your next headless commerce storefronts with Next JS and Bagisto: [https://github.com/bagisto/nextjs-commerce](https://github.com/bagisto/nextjs-commerce)

# Open Source Mobile eCommerce

Revolutionize Your Online Store with Bagisto's Open Source eCommerce Mobile 

![enter image description here](https://raw.githubusercontent.com/bagisto/temp-media/master/open-source-ecommerce-mobile.png)

Mobile eCommerce powered by Flutter & Laravel: https://github.com/bagisto/opensource-ecommerce-mobile-app

# AI Powered eCommerce

You can integrate popular large language models like GPT, BERT, Llama etc. to build [AI-powered eCommerce](https://bagisto.com/en/extensions/laravel-chatbot-using-openai-chatgpt-llm/) applications with bagisto. Some of the popular use cases where you can make use of LLMs to build AI apps are chatbots, automated product descriptions, customer support, search and recommendations. 

![enter image description here](https://raw.githubusercontent.com/bagisto/temp-media/master/ai_powered_ecommerce.png)

You can incorporate LLM API with your bagisto applications to send and receive queries and ensure proper error handling and rate limiting to prevent overuse of the API

# Decentralised eCommerce

Build [decentralised applications](https://bagisto.com/en/services/blockchain-commerce/) with Bagisto on popular blockchains like Ethereum and Solana by integrating smart contracts with the eCommerce platform. You can have decentralised marketplaces, [NFT marketplaces](https://bagisto.com/en/nft-marketplace/), and decentralised e-signing with the laravel eCommerce system.

![enter image description here](https://raw.githubusercontent.com/bagisto/temp-media/master/decentralised-ecommerce.png)

# Commerce For Every Need

![enter image description here](https://raw.githubusercontent.com/bagisto/temp-media/master/every-need.png)

With Bagisto, you can easily create use cases for various commerce needs like Marketplaces, PWA, Native Mobile Apps, Multi-Tenants systems, Blockchain and many more.

# Built In Extensions

![enter image description here](https://raw.githubusercontent.com/bagisto/temp-media/master/built_in_extension.png)

Make use of 100+ Bagisto pre-built extensions from [Bagisto Extension Markeptlace](https://bagisto.com/en/extensions/)


# License
Bagisto is a truly open-source laravel eCommerce framework that will always be free under the [OSL-3.0 License](https://github.com/bagisto/bagisto/blob/master/LICENSE.txt).


