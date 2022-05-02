<?php
defined('JPATH_BASE') or die;
?>
<div id="donate-button-container" class="float-end ms-2 mt-1">
    <div id="donate-button" class="form-control"></div>
    <script src="https://www.paypalobjects.com/donate/sdk/donate-sdk.js" charset="UTF-8"></script>
    <script>
        PayPal.Donation.Button({
            env: 'production',
            hosted_button_id: '4KEBYAH3XK7SW',
            image: {
                src: 'https://www.paypalobjects.com/en_US/i/btn/btn_donate_LG.gif',
                alt: 'Donate with PayPal button',
                title: 'PayPal - The safer, easier way to pay online!',
            }
        }).render('#donate-button');
    </script>
</div>