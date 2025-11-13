<div id="smart-button-container">
</div>
<script src="https://www.paypal.com/sdk/js?client-id=<?php echo getenv('PAYPAL_CLIENT_ID') ?: 'PAYPAL-SANDBOX-CLIENT-ID' ?>&vault=true&intent=subscription" data-sdk-integration-source="button-factory"></script>
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
    const isSecondaryLinkedAccount = "<?php echo $user->isSecondaryLinkedAccount() ?>";

    function initPayPalButton() {
        paypal.Buttons({
            style: {
                shape: 'rect',
                color: 'gold',
                layout: 'vertical',
                label: 'subscribe'
            },

            createSubscription: function(data, actions) {
                return actions.subscription.create({
                    plan_id: 'P-8KY61472AT104171TNDNLI4Y'
                });
            },

            onApprove: function(data, actions) {
                //alert(data.subscriptionID);
                if (data.id === null) {
                    data.id = data.subscriptionID;
                }
                //return actions.subscription.get(data.subscriptionID).then(function(orderData) {
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
                        body: JSON.stringify(data)
                    });
                //});
            },
            
            onError: function(err) {
                console.log(err);
                alert("An error occurred, please send an email to info@sailhousingsolutions.org with the following info:" + "\n " + err);
            }

        }).render('#paypal-button-container');

    }
    const element = document.getElementById('smart-button-container');
    if (isPaid && !isPastDue && !willBePastDueSoon) {
        if (!isSecondaryLinkedAccount) {
            element.innerHTML = '<p>Your support and contribution to SAIL is greatly appreciated. Your account has access to paid member features. Our records reflect that you last paid your dues on ' + lastPaymentDate
        + '. Your SAIL member privileges will expire on ' + expYear + '.</p>';
        }
        else {
            element.innerHTML = '<p>Our records reflect that your account is linked to an account that has paid dues. Your support and contribution to SAIL is greatly appreciated. Your account has access to paid member features.'
        + ' Your SAIL member privileges will expire on ' + expYear + '.</p>';
        }

    } else if (isPaid && !isPastDue && willBePastDueSoon) {
        element.innerHTML = `
        <details>
            <summary>Membership Expires Soon! - Your dues will expire on ` + expYear + `. If you already setup the PayPal subscription, you will be charged soon.</summary>
            <div style="text-align: center;">
            <p>
                If you have not already, use PayPal to subscribe to the $20 yearly SAIL membership dues.
                <div id="paypal-button-container" style="padding-left: 100px;"></div>
            </p>
            </div>
        </details>`;
        initPayPalButton();
    } else if (isPastDue && expYear !== '1985-01-01') {
        element.innerHTML = `
        <details>
            <summary>Membership Expired - If you believe this is a mistake (PayPal charged you but your SAIL account expired) please send an email to info@sailhousingsolutions.org</summary>
            <div style="text-align: center;">
            <p>
                If you have not already, use PayPal to subscribe to the $20 yearly SAIL membership dues.
                <div id="paypal-button-container" style="padding-left: 100px;"></div>
            </p>
            </div>
        </details>`;
        initPayPalButton();
    }  else if (isNewUser) {
        element.innerHTML = `
        <h2>Upgrade Your Membership by Paying Dues</h2>
        <div style="text-align: center;">
            <p>
                Use PayPal to subscribe to the $20 yearly SAIL membership dues.
                <div id="paypal-button-container" style="padding-left: 100px;"></div>
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
                Use PayPal to subscribe to the $20 yearly SAIL membership dues.
                <div id="paypal-button-container" style="padding-left: 100px;"></div>
            </p>
            </div>
        </details>`;
        initPayPalButton();
    }
</script>