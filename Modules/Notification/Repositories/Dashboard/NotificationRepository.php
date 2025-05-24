<?php

namespace Modules\Notification\Repositories\Dashboard;

use Modules\User\Entities\UserFireBaseToken;
use Carbon\Carbon;

class NotificationRepository
{

    function __construct(UserFireBaseToken $token)
    {
        $this->token = $token;
    }

    public function getAllTokens()
    {
        return $this->token->pluck('firebase_token')->toArray();
    }

    public function getAllUserTokens($userId)
    {
        return $this->token->where('user_id', $userId)->pluck('firebase_token')->toArray();
    }

}
