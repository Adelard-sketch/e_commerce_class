# Paystack test payments

Checkout uses Paystack's hosted payment page and charges in Ghana cedis (GHS). The application initializes payments on the server and verifies each transaction with Paystack before marking it successful or clearing the cart.

## Configure test mode

1. Create a Paystack account and switch to **Test Mode**.
2. For file-based configuration, copy `core/paystack_credentials.example.php` to `core/paystack_credential.php` and enter your test public key, test secret key, and callback URL. `core/paystack_credential.php` is excluded from Git. Never share or commit it; the secret key is only used by PHP. Alternatively, set `PAYSTACK_SECRET_KEY` and `PAYSTACK_CALLBACK_URL` in the PHP environment. Environment variables remain available as fallbacks.
3. Set the callback URL to the full address of `actions/paystack_callback_action.php`, reachable in the browser after hosted checkout. For example, on the school server: `http://169.239.251.102:442/~borauzima.adelard/ecommerce-class/ecomlab/actions/paystack_callback_action.php`. Use HTTPS instead if the school server has it enabled. For local XAMPP, `core/db_cred.php` defaults to `http://localhost/ecomlab/actions/paystack_callback_action.php`.
4. Ensure the PHP process can make HTTPS requests to `api.paystack.co` and that PHP URL stream wrappers are enabled.
5. Sign in, add a product to the cart, and select **Pay with Paystack**. Use Paystack's current test payment details from its dashboard/documentation; test mode does not charge real money.

For local XAMPP, set `PAYSTACK_SECRET_KEY` in Apache's configuration and restart Apache. On the school server, set the payment key in the hosting control panel's PHP environment settings (or Apache configuration if available). Set the callback URL in the Apache virtual host configuration:

```apache
SetEnv PAYSTACK_CALLBACK_URL "http://169.239.251.102:442/~borauzima.adelard/ecommerce-class/ecomlab/actions/paystack_callback_action.php"
```

Restart Apache after changing its configuration. If the site is served over HTTPS, use its `https://` callback URL instead. On shared hosting without Apache configuration access, set `PAYSTACK_CALLBACK_URL` in the hosting control panel's PHP environment settings. The callback address must be reachable by the customer's browser. The current hosted-checkout flow does not use the public key; it is retained in the credentials file for configurations that need it.

When deploying for real payments, configure the live secret key only in the server environment and confirm the account's live payment settings first.
