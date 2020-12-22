<?php

return [
    // authtype can be local or ldap
    'enable-local' => false,
    'local' => [

    ],

    'enable-ldap' => true,
    'ldap' => [
        'host' => 'your-ldap-hostname',
        'baseDn' => 'dc=work,dc=group',
        'searchUserName' => '<username for a search user>',
        'searchUserPassword' => '<password for a search user>',

        // optional parameters and their default values
        'ldapVersion' => 3,             // LDAP version
        'protocol' => 'ldaps://',       // Protocol to use
        'followReferrals' => false,     // If connector should follow referrals
        'port' => 636,                  // Port to connect to
        'loginAttribute' => 'uid',      // Identifying user attribute to look up for
        'ldapObjectClass' => 'person',  // Class of user objects to look up for
        'timeout' => 10,                // Operation timeout, seconds
        'connectTimeout' => 5,          // Connect timeout, seconds
    ],

    'enable-saml' => false,
    'saml' => [

    ],
];