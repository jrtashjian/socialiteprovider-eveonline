<?php

namespace SocialiteProviders\EveOnline;

use SocialiteProviders\Manager\SocialiteWasCalled;

class EveOnlineExtendSocialite
{
    /**
     * Execute the provider.
     */
    public function handle(SocialiteWasCalled $socialiteWasCalled)
    {
        $socialiteWasCalled->extendSocialite('eveonline', __NAMESPACE__.'\Provider');
    }
}
