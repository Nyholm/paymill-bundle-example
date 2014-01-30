# paymill-bundle-example

Exmple Symfony app that uses [paymill-bundle](https://github.com/memeoirs/paymill-bundle) to process payments.

## Setup
First enter your database and paymill credentials in `parameters.yml`. Then run:

    composer install
    app/console doctrine:database:create
    app/console doctrine:schema:update
    app/console server:run

You can access the credit card form at [localhost:8001](http://localhost:8001)

## How it works
At the end of your app's checkout workflow, you'll have a form where your customer enters his credit card information. `paymill-bundle` comes packaged with a template to include in your page which renders such form. You are free to change how it looks in any way you see fit by changing its markup and/or CSS. See [OrdersController::checkout](src/Memeoirs/PaymillExampleBundle/Controller/OrdersController.php).

In order for you to not have to worry about PCI compliance, the credit card information your customers enter in the form should never reach your servers. This is where [Paymill's Bridge](https://www.paymill.com/en-gb/documentation-3/reference/paymill-bridge/) comes into action: it's a javascript library that makes an Ajax request to Paymill's servers containing the credit card information. The response to this request is a unique `token`.

You then submit the form to your server through Ajax, excluding the credit card information but including the `token` returned from Paymill. In your controller, this `token` will be used to make a request to Paymill's API and [create a transaction](https://www.paymill.com/it-it/documentation-3/reference/api-reference/#create-new-transaction-with), aka a Payment. If the `transaction` was successfuly created, the money was transferred to your Paymill account and you're done.
