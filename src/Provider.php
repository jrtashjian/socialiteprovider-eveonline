<?php

namespace SocialiteProviders\EveOnline;

use Laravel\Socialite\Two\ProviderInterface;
use SocialiteProviders\Manager\OAuth2\AbstractProvider;
use SocialiteProviders\Manager\OAuth2\User;

class Provider extends AbstractProvider implements ProviderInterface
{
    /**
     * Unique Provider Identifier.
     */
    const IDENTIFIER = 'EVEONLINE';

    /**
     * {@inheritdoc}
     */
    protected $scopes = [];

    /**
     * {@inheritdoc}
     */
    protected $scopeSeparator = ' ';

    /**
     * {@inheritdoc}
     */
    protected function getAuthUrl($state)
    {
        return $this->buildAuthUrlFromBase('https://login.eveonline.com/oauth/authorize', $state);
    }

    /**
     * {@inheritdoc}
     */
    protected function getTokenUrl()
    {
        return 'https://login.eveonline.com/oauth/token';
    }

    /**
	 * {@inheritdoc}
	 */
	public function getAccessToken( $code )
	{
		$response = $this->getHttpClient()->post( $this->getTokenUrl(), [
			'headers'     => [ 'Authorization' => 'Basic ' . base64_encode( $this->clientId . ':' . $this->clientSecret ) ],
			'form_params' => $this->getTokenFields( $code ),
		] );

		$this->credentialsResponseBody = json_decode( $response->getBody(), true );

		return $this->parseAccessToken( $response->getBody() );
	}

    /**
     * {@inheritdoc}
     */
    protected function getUserByToken($token)
    {
        $response = $this->getHttpClient()->get('https://login.eveonline.com/oauth/verify', [
            'headers' => [
                'Authorization' => 'Bearer '.$token,
            ],
        ]);

        return json_decode($response->getBody(), true);
    }

    /**
     * {@inheritdoc}
     */
    protected function mapUserToObject(array $user)
    {
        return (new User())->setRaw($user)->map([
            'id'       => $user['CharacterID'],
            'nickname' => $user['CharacterName'],
            'name'     => $user['CharacterName'],
            'email'    => $user['CharacterID'] . "@local.com",
            'avatar'   => "https://image.eveonline.com/Character/" . $user['CharacterID'] . "_256.jpg",
        ]);
    }

    /**
     * {@inheritdoc}
     */
    protected function getTokenFields($code)
    {
        return array_merge(parent::getTokenFields($code), [
            'grant_type' => 'authorization_code'
        ]);
    }
}
