<?php

class LAmazonConfig
{
    public static $amazon_config = array(
        "AWS_ACCESS_KEY_ID"=>"AKIAJ4OQW2ETQ6O2HBNA",
        "AWS_SECRET_ACCESS_KEY"=>"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",
        "APPLICATION_NAME"=>"LAmazon",
        "APPLICATION_VERSION"=>"demo1.0",
        "ServiceUrlReport" => "https://mws.amazonservices.co.uk",
        "ServiceUrlProduct" => "https://mws-eu.amazonservices.com/Products/2011-10-01",
        "ServiceUrlSeller"=>"https://mws-eu.amazonservices.com/Sellers/2011-07-01",
        "serviceUrlInventory" => "https://mws-eu.amazonservices.com/FulfillmentInventory/2010-10-01",
        "MARKETPLACE_ID"=>"A1F83G8C2ARO7P",
        "ServiceUrlSubmitDE"=>"https://mws.amazonservices.de",
        "MERCHANT_ID" => "AB0EMHVN49K0J",
        "token"       => "amzn.mws.02b4f06d-85c4-26fd-f030-06456b5a1dfa"
    );

    public static $marketplace_id = array(
        "Germany" => array(
                    "code" => "DE",
                    "endPoint" => "https://mws-eu.amazonservices.com",
                    "MarketplaceId" => "A1PA6795UKMFR9"
                ),
        "UK"     => array(
                    'code' => "GB",
                    'endPoint' => "https://mws-eu.amazonservices.com",
                    'MarketplaceId' => "A1F83G8C2ARO7P"
                )
    );
}


