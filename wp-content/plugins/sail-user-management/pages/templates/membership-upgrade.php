<div id="smart-button-container">
</div>
<script src="https://www.paypal.com/sdk/js?client-id=<?php echo getenv('PAYPAL_CLIENT_ID') ?: 'PAYPAL-SANDBOX-CLIENT-ID' ?>&enable-funding=venmo&currency=USD&disable-funding=credit" data-sdk-integration-source="button-factory"></script>
<script>
    <?php
    use Sail\Data\Dao\UserDao;

    $dao = UserDao::getInstance();
    $user = $sailUser = UserDao::getInstance()->getSailUser();
    ?>
    const isPaid = <?php echo $user->is_due_paying_user() ? 'true' : 'false' ?>;
    const isNewUser = <?php echo $isNewMember ? 'true' : 'false' ?>;

    function initPayPalButton() {
        paypal.Buttons({
            style: {
                shape: 'rect',
                color: 'gold',
                layout: 'vertical',
                label: 'paypal',

            },

            createOrder: function(data, actions) {
                return actions.order.create({
                    purchase_units: [{
                        "description": "2021-2022 SAIL Membership",
                        "amount": {
                            "currency_code": "USD",
                            "value": 30
                        }
                    }]
                });
            },

            onApprove: function(data, actions) {
                return actions.order.capture().then(function(orderData) {
                    // Show a success message within this page.
                    const element = document.getElementById('smart-button-container');
                    element.innerHTML = `<p>Thank you for upgrading your membership! 
                    Your dues will help to fund SAIL events and you will now have access to Friendship Connect and the full housing roadmap.</p>`;
                    if (isNewUser) {
                        element.innerHTML += `<p><a href='<? echo Sail\Utils\WebUtils::getUrl() ?>/user'>
                    Click here to go to your account profile page.
                </p></a>`
                    }

                    // Post membership update request
                    // Need nonce for auth on top of cookie for Wordpress REST API
                    // https://developer.wordpress.org/rest-api/using-the-rest-api/authentication/
                    fetch('/wp-json/membership/v1/dues', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-WP-Nonce': '<?php echo wp_create_nonce('wp_rest') ?>'
                        },
                        body: JSON.stringify(orderData)
                    });
                });
            },

            onError: function(err) {
                console.log(err);
            }
        }).render('#paypal-button-container');
    }
    const element = document.getElementById('smart-button-container');
    if (isPaid) {
        element.innerHTML = '<p>Your account has access to paid member features. Your access will expire at the end of 2022</p>';
    } else if (isNewUser) {
        element.innerHTML = `
        <h2>Upgrade Your Membership by Paying Dues</h2>
        <div style="text-align: center;">
            <p>
                Use PayPal to pay the $30 SAIL membership due.
                <div id="paypal-button-container"></div>
            </p>
            <p>
                This step is optional, but some features, like Friendship Connect and the full Housing Roadmap, will not be accessible. 
                You can upgrade to a paid member at any time using your account profile page.
            </p>
            <p><a href='<? echo Sail\Utils\WebUtils::getUrl() ?>user'>
                Don't want to pay now? Click here to go to your account profile page where you can upgrade your membership at any time.
            </p></a>
        </div>`;
        initPayPalButton();
    } else {
        element.innerHTML = `
    <details>
        <summary>Upgrade Your Membership - Pay Dues</summary>
        <div style="text-align: center;">
        <p>
            Use PayPal to pay the $30 SAIL membership due.
            <div id="paypal-button-container"></div>
        </p>
        </div>
    </details>`;
        initPayPalButton();
    }
</script>