<div>
    <button id="newsletter-subscribe-button" style="display: none; border: none" class="wp-block-button__link has-vivid-cyan-blue-background-color has-background">Subscribe</a>
</div>

<script>
    <?php
    use Sail\Constants;
    ?>

    const subscribedId = 'subscribed-message';
    function onClick() {
        let promise;
        const email = document.getElementById('email-text-input');
        const isLoggedIn = document.getElementsByTagName('body')[0].classList.contains("logged-in")
        if (isLoggedIn) {
            promise = fetch('<?php echo Constants::API_PREFIX ?>' + 'newsletter/v1/subscribe', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-WP-Nonce': '<?php echo wp_create_nonce('wp_rest') ?>'
                },
                body: '{}'
            });
        } else if (email && email.value) {
            promise = fetch('<?php echo Constants::API_PREFIX ?>' + 'newsletter/v1/subscribe', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: '{"email": "' + email.value + '"}'
            });
        }
        promise.then(response => response.json())
            .then(data => {
                if (data.status == 'subscribed') {
                    showSubscribedMessage();
                } else {
                    console.log(data);
                }
            }).catch(err => console.error(err));
    }

    function showNonMemberSubscribe() {
        let button = document.getElementById('newsletter-subscribe-button');
        button.style.display = 'block';
        let email = document.createElement('input');
        email.type = "text";
        email.id = "email-text-input";
        email.style.display = 'block';
        email.style.marginBottom = '2em';
        button.parentNode.prepend(email);
        let label = document.createElement('p');
        label.id = "email-label";
        label.innerText = 'Enter your email address to subscribe to the newsletter:';
        button.parentNode.prepend(label);
        button.onclick = onClick;
        subMessage = document.getElementById(subscribedId);
        if (subMessage) {
            subMessage.style.display = 'none';
        }
    }

    // TODO refactor this nightmare
    function showSubscribedMessage() {
        let button = document.getElementById('newsletter-subscribe-button');
        button.style.display = 'none';
        let email = document.getElementById('email-text-input');
        if (email) {
            email.style.display = 'none';
        }
        let label = document.getElementById('email-label');
        if (label) {
            label.style.display = 'none';
        }
        let subMessage = document.createElement('p');
        subMessage.id =  subscribedId;
        subMessage.innerText = 'You are currently subscribed to the SAIL newsletter.';
        subMessage.style.display = 'block';
        button.parentNode.appendChild(subMessage);
    }

    function showSubscribeButton() {
        let button = document.getElementById('newsletter-subscribe-button');
        button.style.display = 'block';
        button.onclick = onClick;
        subMessage = document.getElementById(subscribedId);
        if (subMessage) {
            subMessage.style.display = 'none';
        }
    }

    window.onload = function() {
        const isSubscribed = <?php echo $isSubscribed ? 'true' : 'false' ?>;
        if (isSubscribed) {
            showSubscribedMessage();
        } else if (document.getElementsByTagName('body')[0].classList.contains("logged-in")) {
            showSubscribeButton();
        } else {
            showNonMemberSubscribe();
        }
    }
</script>
