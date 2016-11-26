# EVE Online OAuth2 Provider for Laravel Socialite

A Laravel Socialite provider for EVE Online SSO.

## Installation

### 1. Composer

	// This assumes that you have composer installed globally
	composer require jrtashjian/socialiteproviders-eveonline

### 2. Service Provider

- Remove `Laravel\Socialite\SocialiteServiceProvider` from your `providers[]` array in `config\app.php` if you have added it already.
- Add `\SocialiteProviders\Manager\ServiceProvider::class` to your `providers[]` array in `config\app.php`.

For Example:

	'providers' => [
		// a whole bunch of providers
		// remove 'Laravel\Socialite\SocialiteServiceProvider',
		\SocialiteProviders\Manager\ServiceProvider::class, // add
	];
	
### 3. Add the Event and Listeners

- Add `SocialiteProviders\Manager\SocialiteWasCalled` event to your `listen[]` array in `<app_name>/Providers/EventServiceProvider`.
- Add your listeners (i.e. the ones from the providers) to the `SocialiteProviders\Manager\SocialiteWasCalled[]` that you just created.
- The listener that you add for this provider is `'SocialiteProviders\EveOnline\EveOnlineExtendSocialite@handle',`.
- Note: You do not need to add anything for the built-in socialite providers unless you override them with your own providers.

For example:

	/**
	 * The event handler mappings for the application.
	 *
	 * @var array
	 */
	protected $listen = [
		\SocialiteProviders\Manager\SocialiteWasCalled::class => [
			// add your listeners (aka providers) here
			'SocialiteProviders\EveOnline\EveOnlineExtendSocialite@handle',
		],
	];

### 4. Environment Variables

If you add environment values to your .env as exactly shown below, **you do not need to add an entry to the services array.**

#### Append provider values to your `.env` file

	// other values above
	EVEONLINE_KEY=yourkeyfortheservice
	EVEONLINE_SECRET=yoursecretfortheservice
	EVEONLINE_REDIRECT_URI=https://example.com/login   

#### Add to `config/services.php`.

**You do not need to add this if you add the values to the .env exactly as shown above.** The values below are provided as a convenience in the case that a developer is not able to use the .env method

	'eveonline' => [
		'client_id' => env('EVEONLINE_KEY'),
		'client_secret' => env('EVEONLINE_SECRET'),
		'redirect' => env('EVEONLINE_REDIRECT_URI'),  
	],

## Usage

You should now be able to use it like you would regularly use Socialite (assuming you have the facade installed):

	return Socialite::driver('eveonline')->redirect();
	
## Issues

If you have any issues using this package, [create a new Issue](https://github.com/jrtashjian/socialiteprovider-eveonline/issues/new).

## License

This code is licensed under the MIT License.