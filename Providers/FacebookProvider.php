<?php

namespace Benji07\SsoBundle\Providers;

/**
 * Facebook Provider
 *
 * @author Benjamin Lévêque <benjamin@leveque.me>
 */
class FacebookProvider extends OAuth2Provider
{
    protected $options = array(
        'authorizeUrl'   => 'https://www.facebook.com/dialog/oauth',
        'accessTokenUrl' => 'https://graph.facebook.com/oauth/access_token',
        'profileUrl'     => 'https://graph.facebook.com/me',
        'scope'          => ''
    );

    /**
     * Retrieve user data or null if no user found
     *
     * @return array
     */
    public function getUserData()
    {
        $data = parent::getUserData();
        // graph 2.3 change
        $data = json_decode(key($data), true);

        if (isset($data['access_token'])) {
            $parameters = array(
                'access_token' => $data['access_token'],
                'fields'       => 'email,name',
            );

            $url = $this->getOption('profileUrl') . '?' . http_build_query($parameters);

            $response = json_decode(file_get_contents($url), true);

            return array_merge($data, $response);
        }

        return $data;
    }
}
