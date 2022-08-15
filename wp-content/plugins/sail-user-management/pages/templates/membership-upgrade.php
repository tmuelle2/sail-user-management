<div id="smart-button-container">
</div>
<script src="https://www.paypal.com/sdk/js?client-id=<?php echo getenv('PAYPAL_CLIENT_ID') ?: 'PAYPAL-SANDBOX-CLIENT-ID' ?>&enable-funding=venmo&currency=USD&disable-funding=credit" data-sdk-integration-source="button-factory"></script>
<script>
    <?php
    use Sail\Data\Dao\UserDao;
    use Sail\Constants;

    $dao = UserDao::getInstance();
    $user = $sailUser = UserDao::getInstance()->getSailUser();
    ?>
    const isPaid = <?php echo $user->isDuePayingUser() ? 'true' : 'false' ?>;
    const isNewUser = <?php echo $isNewMember ? 'true' : 'false' ?>;
    const willBePastDueSoon = <?php echo $user->willBePastDueSoon() ? 'true' : 'false' ?>;
    const isPastDue = <?php echo $user->isPastDue() ? 'true' : 'false' ?>;
    const lastPaymentDate = "<?php echo $sailUser->getDatabaseData()["lastDuePaymentDate"] ?>";
    const expYear = "<?php echo $user->calculateExpirationYear() ?>";

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
                        "description": "2022-2023 SAIL Membership",
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
                        element.innerHTML += `<p><a href='/user'>
                    Click here to go to your account profile page.
                </p></a>`
                    }

                    // Post membership update request
                    // Need nonce for auth on top of cookie for Wordpress REST API
                    // https://developer.wordpress.org/rest-api/using-the-rest-api/authentication/
                    fetch('<?php echo Constants::API_PREFIX ?>' + 'membership/v1/dues', {
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
    if (isPaid && !isPastDue && !willBePastDueSoon) {
        element.innerHTML = '<p>Your account has access to paid member features. You last paid your dues on ' + lastPaymentDate
        + '. Your access will expire at the end of ' + expYear + '.</p>';
    } else if (isPaid && !isPastDue && willBePastDueSoon) {
        element.innerHTML = `
        <details>
            <summary>Membership Expires Soon! - Pay Annual Dues</summary>
            <div style="text-align: center;">
            <p>
                Use PayPal to pay the $30 SAIL membership due.
                <div id="paypal-button-container"></div>
            </p>
            </div>
        </details>`;
        initPayPalButton();
    } else if (isPastDue) {
        element.innerHTML = `
        <details>
            <summary>Membership Expired - Pay Annual Dues</summary>
            <div style="text-align: center;">
            <p>
                Use PayPal to pay the $30 SAIL membership due.
                <div id="paypal-button-container"></div>
            </p>
            </div>
        </details>`;
        initPayPalButton();
    }  else if (isNewUser) {
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
            <p><a href='/user'>
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